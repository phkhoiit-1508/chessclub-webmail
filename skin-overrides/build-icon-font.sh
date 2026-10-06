#!/usr/bin/env bash
# Regenerates fonts/material-symbols-rounded.woff2 as a subset that contains exactly the
# icons listed in _icons.less. Needs network access (Google Fonts CSS API); the result is
# self-hosted, nothing is fetched from Google at runtime.
set -euo pipefail

here="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
names="$(grep -o '^@fa-var-[a-z0-9-]*: *"[a-z0-9_]*"' "$here/_icons.less" | sed 's/.*"\(.*\)"/\1/' | sort -u | paste -sd, -)"
api="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20,100..700,0..1,0&icon_names=${names}&display=block"

# a modern UA makes the API answer with woff2
css="$(curl -fsS -A 'Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0' "$api")"
url="$(printf '%s' "$css" | grep -o 'https://fonts.gstatic.com[^)]*' | head -1)"
[ -n "$url" ] || { echo "no font url in the API response (invalid icon name?)" >&2; exit 1; }

mkdir -p "$here/fonts"
curl -fsS -o "$here/fonts/material-symbols-rounded.woff2" "$url"
echo "Wrote fonts/material-symbols-rounded.woff2 ($(wc -c < "$here/fonts/material-symbols-rounded.woff2") bytes, $(printf '%s' "$names" | tr ',' '\n' | wc -l) icons)"
