# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A Roundcube Webmail 1.7.x install (PHP, MariaDB) run via Docker, fronting Gmail over IMAP/SMTP. It is **not a git repository** and is a *release package*, not a dev checkout: there is no `tests/` directory, no `package.json`/`node_modules`, no `jsdeps.json`, and no linter/phpstan config. Don't expect a test suite or lint commands to exist.

- `docker-compose.yml` (repo root) — `roundcube/roundcubemail:latest` + `mariadb:10.11`. Serves on port 80. The host's `./source_code` is bind-mounted over `/var/www/html` in the container, so edits to `source_code/` take effect immediately (no rebuild). Edits made as root inside the container may create root-owned files on the host (many files in `source_code/` are already root-owned).
- `source_code/` — the Roundcube tree (the web root is `source_code/public_html`; `source_code/index.php` just exits with a message).

## Commands

```
docker compose up -d                 # start (from the repo root)
docker compose logs -f roundcube     # logs go to stdout (log_driver = stdout)
docker compose exec roundcube bash   # shell in the container (php, bin/*.sh live here)
```

Maintenance scripts are in `source_code/bin/` (run inside the container, from `/var/www/html`): `initdb.sh`, `updatedb.sh` (apply `SQL/` migrations), `cleandb.sh`, `gc.sh`, `deluser.sh`, `moduserprefs.sh`, `healthcheck.sh`, `msgimport.sh`/`msgexport.sh`. `bin/update.sh` / `bin/installto.sh` are for upgrading a release in place.

Dependencies are managed with Composer (`composer.json`, `vendor/` is committed to the tree). `composer.json` differs from `composer.json-dist` only by a trailing newline.

Skin CSS (Elastic) is compiled from LESS: `skins/elastic/Makefile` has `css` target (`npx lessc --clean-css ...`) producing `styles.min.css`, `print.min.css`, `embed.min.css`. The `.min.css` files are what the browser loads — edit the `.less` sources and recompile, or edit the `.min.css` directly if Node tooling isn't available. Same pattern for JS: `*.min.js` sits next to `*.js` (e.g. `skins/elastic/ui.js`, `plugins/archive/archive.js`); the min file may be what's served, depending on `devel_mode`/`use_minified` behavior, so keep the pair in sync. Root `Makefile` is only for building release tarballs; it is not useful for development here.

## Configuration (layered — read in this order)

1. `source_code/config/defaults.inc.php` — all defaults, heavily commented; don't edit, override instead.
2. `source_code/config/config.inc.php` — site overrides. Sets `plugins = []`, log driver, zipdownload, `des_key`, spellcheck (pspell), then **`include`s `config.docker.inc.php`**.
3. `source_code/config/config.docker.inc.php` — DB DSN (`roundcubedb` host), Gmail IMAP/SMTP hosts, skin `elastic`, temp dir, and appends the plugin list `archive, zipdownload, contextmenu, markasjunk`.

Things to be aware of:
- The same settings are also passed as `ROUNDCUBEMAIL_*` env vars in `docker-compose.yml`; the image's entrypoint may regenerate/override config from them, so when a setting "doesn't stick", check both places.
- `contextmenu` is enabled in config but **does not exist** in `source_code/plugins/` (it's a third-party plugin; the image's composer step or a manual install is needed). `markasjunk`, `archive`, `zipdownload` are bundled.
- Credentials (DB passwords, `des_key`) are plaintext in `docker-compose.yml` and `config/`. Treat them as dev values; don't copy them into new files.
- `source_code/uscc_logo.{png,svg}` are custom branding assets at the tree root; branding is wired via `$config['skin_logo']` (see `defaults.inc.php` ~line 551 for the format) — it is not currently set in the config files.

## Architecture

Roundcube is a server-rendered PHP app with a jQuery front end; requests flow through a single entry point into "actions" that render templates.

- **Entry point**: `public_html/index.php` → bootstraps `program/include/iniset.php` (autoloading via the Composer classmap over `program/actions/`, `program/include/`, `program/lib/`) → `rcmail` singleton. Other entry points: `public_html/static.php` (static asset serving), `public_html/installer.php` (web installer; disable in production).
- **Framework layer** (`program/lib/Roundcube/`, the standalone "Roundcube Framework", classes `rcube_*`): config (`rcube_config`), DB abstraction (`db/`, `rcube_db`), IMAP client (`rcube_imap_generic` raw protocol → `rcube_imap` storage with `rcube_imap_cache`), SMTP, sessions (`session/`), caching (`cache/`), MIME/HTML sanitizing (`rcube_washtml`, `rcube_mime`), address books (`rcube_contacts`, `rcube_ldap`), and the plugin API (`rcube_plugin_api`, `rcube_plugin`).
- **Application layer** (`program/include/`, classes `rcmail_*`): `rcmail` extends `rcube` and owns request dispatch; `rcmail_output_html` / `_json` / `_cli` render the response; `rcmail_sendmail` handles composing/sending.
- **Actions** (`program/actions/{mail,contacts,settings,login,utils}/`): one class per `_task`/`_action` pair (e.g. `?_task=mail&_action=send` → `program/actions/mail/send.php`), all extending `rcmail_action`. Adding behavior usually means a new/edited action class plus its template and client JS.
- **Templates & UI**: skins live in `skins/<name>/` (only `elastic` here). HTML templates in `skins/elastic/templates/` use Roundcube's `<roundcube:...>` template tags and are filled by the output class; client behavior is in `program/js/` (`app.js` etc.) plus the skin's `ui.js`. Strings are in `program/localization/<lang>/`.
- **Plugins** (`plugins/<name>/<name>.php`): a class extending `rcube_plugin`, with `init()` registering hooks (`$this->add_hook(...)`) and actions (`register_action`). Each has `localization/`, optional JS, and a `composer.json`. Enabled via `$config['plugins']`. Prefer a plugin or a config override over patching `program/` so upgrades (`bin/update.sh`) don't clobber changes.
- **Storage**: app data (users, identities, contacts, prefs, caches) in MariaDB, schema in `SQL/`; mail itself stays on Gmail's IMAP server (nothing is stored locally except the optional IMAP/message caches enabled in compose).

## Upstream docs

`source_code/docs/` (INSTALL.md, UPGRADING.md, SECURITY.md, RELEASE_MANAGEMENT.md) and `source_code/CHANGELOG.md` are the upstream references. `program/lib/Roundcube/README.md` documents the framework layer.
