FROM roundcube/roundcubemail:1.7.5-apache AS roundcube

FROM node:20-alpine AS skin-builder
COPY --from=roundcube /usr/src/roundcubemail/skins/elastic /skin
WORKDIR /skin

COPY skin-overrides/_variables.less skin-overrides/_brand.less skin-overrides/_icons.less styles/
COPY skin-overrides/fonts/material-symbols-rounded.woff2 fonts/
COPY skin-overrides/favicon.ico images/favicon.ico
COPY skin-overrides/watermark.html watermark.html
COPY skin-overrides/images/uscc_login.png skin-overrides/images/uscc_small.png /skin/images/
COPY skin-overrides/images/uscc_dark.svg skin-overrides/images/uscc_blue.png /skin/images/
COPY skin-overrides/uscc-bg.jpg images/uscc-bg.jpg

RUN grep -q '"_brand"' styles/styles.less \
    || printf '\n@import (optional) "_brand";\n' >> styles/styles.less
RUN sed -i 's/#f4f4f4/#f3f5fb/g' meta.json
RUN npm install --no-save --no-audit --no-fund less less-plugin-clean-css \
    && for file in styles print embed; do \
        npx lessc --clean-css="--s1 --advanced" "styles/${file}.less" > "/tmp/${file}.min.css"; \
        test -s "/tmp/${file}.min.css"; \
        cp "/tmp/${file}.min.css" "styles/${file}.min.css"; \
    done

FROM roundcube/roundcubemail:1.7.5-apache
COPY --from=skin-builder /skin /usr/src/roundcubemail/skins/elastic
