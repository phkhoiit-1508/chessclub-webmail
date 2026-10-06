#!/usr/bin/env bash
# Installs the USCC overrides into the (root-owned) Elastic skin and rebuilds its CSS.
# Run from anywhere:  sudo ./install.sh      (needs root, plus docker for the LESS build)
set -euo pipefail

here="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
skin="$here/../source_code/skins/elastic"
stamp="$(date +%Y%m%d-%H%M%S)"

# 1. back up everything we are about to touch
mkdir -p "$here/backup-$stamp"
cp -a "$skin/styles/styles.less" "$skin/styles/styles.min.css" "$skin/styles/print.min.css" \
      "$skin/styles/embed.min.css" "$skin/meta.json" "$skin/images/favicon.ico" "$skin/watermark.html" "$here/backup-$stamp/"
[ -f "$skin/styles/_icons.less" ] && cp "$skin/styles/_icons.less" "$here/backup-$stamp/"
[ -f "$skin/fonts/material-symbols-rounded.woff2" ] && cp "$skin/fonts/material-symbols-rounded.woff2" "$here/backup-$stamp/"

# 2. install the override files
cp "$here/_variables.less" "$here/_brand.less" "$here/_icons.less" "$skin/styles/"
mkdir -p "$skin/fonts"
cp "$here/fonts/material-symbols-rounded.woff2" "$skin/fonts/"
cp "$here/favicon.ico" "$skin/images/favicon.ico"
cp "$here/watermark.html" "$skin/watermark.html"
cp "$here/images/uscc_login.png" "$here/images/uscc_small.png" "$here/images/uscc_dark.svg" "$skin/images/"
cp "$here/uscc-bg.jpg" "$skin/images/uscc-bg.jpg"

# 3. hook _brand.less into styles.less (once)
grep -q '"_brand"' "$skin/styles/styles.less" || printf '\n@import (optional) "_brand";\n' >> "$skin/styles/styles.less"

# 4. browser theme-color: #f4f4f4 -> navy-50
sed -i 's/#f4f4f4/#f3f5fb/g' "$skin/meta.json"

# 5. rebuild CSS (the Makefile calls `npx lessc`; install less locally in a throwaway node container)
# Compile into /tmp first and copy over only if all three succeed: the Makefile
# redirects straight into the .min.css files, so a LESS error would leave them empty.
docker run --rm -v "$skin":/w -w /w node:20 sh -c '
  set -e
  npm install --no-save --no-audit --no-fund less less-plugin-clean-css >/dev/null
  for f in styles print embed; do
    npx lessc --clean-css="--s1 --advanced" styles/$f.less > /tmp/$f.min.css
    test -s /tmp/$f.min.css
  done
  cp /tmp/styles.min.css /tmp/print.min.css /tmp/embed.min.css styles/
'

echo "Done. Backup in $here/backup-$stamp. Hard-refresh the browser (Ctrl+Shift+R)."
echo "Check: grep -c -i '#37beff' $skin/styles/styles.min.css   # expect 0"
