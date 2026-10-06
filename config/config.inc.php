<?php

/* Local configuration for Roundcube Webmail */

// ----------------------------------
// SQL DATABASE
// ----------------------------------
// Database connection string (DSN) for read+write operations
// Format (compatible with PEAR MDB2): db_provider://user:password@host/database
// Currently supported db_providers: mysql, pgsql, sqlite
// For examples see https://pear.php.net/manual/en/package.database.mdb2.intro-dsn.php
// Note: for SQLite use absolute path (Linux): 'sqlite:////full/path/to/sqlite.db?mode=0646'
//       or (Windows): 'sqlite:///C:/full/path/to/sqlite.db'
// Note: Various drivers support various additional arguments for connection,
//       for Mysql: key, cipher, cert, capath, ca, verify_server_cert, emulate_prepares
//       for Postgres: application_name, sslmode, sslcert, sslkey, sslrootcert, sslcrl, sslcompression, service.
//       e.g. 'mysql://roundcube:@localhost/roundcubemail?verify_server_cert=false'
$config['db_dsnw'] = 'mysql://roundcube:roundcube_pass@roundcubedb:3306/roundcubemail';

// ----------------------------------
// LOGGING/DEBUGGING
// ----------------------------------
// log driver:  'syslog', 'stdout', 'file', or 'php'.
$config['log_driver'] = 'stdout';

// ----------------------------------
// IMAP
// ----------------------------------
// The IMAP host (and optionally port number) chosen to perform the log-in.
// Leave blank to show a textbox at login, give a list of hosts
// to display a pulldown menu or set one host as string.
// Enter hostname with prefix ssl:// to use Implicit TLS, or tls:// for STARTTLS.
// If port number is omitted it will be set to 993 (for ssl://) or 143 otherwise.
// Supported replacement variables:
// %n - hostname ($_SERVER['SERVER_NAME'])
// %t - hostname without the first part
// %d - domain (http hostname, $_SERVER['HTTP_HOST'] without the first part)
// %s - domain name after the '@' from e-mail address provided at login screen
// For example %n = mail.domain.tld, %t = domain.tld
// WARNING: After hostname change update of mail_host column in users table is
//          required to match old user data records with the new host.
$config['imap_host'] = 'ssl://imap.gmail.com:993';

// IMAP connection timeout, in seconds. Default: 0 (use default_socket_timeout)
$config['imap_timeout'] = 15;

// Type of IMAP indexes cache. Supported values: 'db', 'apcu', 'redis' and 'memcache' or 'memcached'.
$config['imap_cache'] = 'db';

// Enables messages cache. Only 'db' cache is supported.
// This requires an IMAP server that supports QRESYNC and CONDSTORE
// extensions (RFC7162). See synchronize() in program/lib/Roundcube/rcube_imap_cache.php
// for further info, or if you experience syncing problems.
$config['messages_cache'] = true;

// ----------------------------------
// SMTP
// ----------------------------------
// SMTP server host (and optional port number) for sending mails.
// Enter hostname with prefix ssl:// to use Implicit TLS, or tls:// for STARTTLS.
// If port number is omitted it will be set to 465 (for ssl://) or 587 otherwise.
// Supported replacement variables:
// %h - user's IMAP hostname
// %n - hostname ($_SERVER['SERVER_NAME'])
// %t - hostname without the first part
// %d - domain (http hostname, $_SERVER['HTTP_HOST'] without the first part)
// %z - IMAP domain (IMAP hostname without the first part)
// For example %n = mail.domain.tld, %t = domain.tld
// To specify different SMTP servers for different IMAP hosts provide an array
// of IMAP host (no prefix or port) and SMTP server e.g. ['imap.example.com' => 'smtp.example.net']
$config['smtp_host'] = 'ssl://smtp.gmail.com:465';

// SMTP connection timeout, in seconds. Default: 0 (use default_socket_timeout)
// Note: There's a known issue where using ssl connection with
// timeout > 0 causes connection errors (https://bugs.php.net/bug.php?id=54511)
$config['smtp_timeout'] = 15;

// provide an URL where a user can get support for this Roundcube installation
// PLEASE DO NOT LINK TO THE ROUNDCUBE.NET WEBSITE HERE!
$config['support_url'] = '';

// Location of temporary saved files such as attachments and cache files
// must be writeable for the user who runs PHP process (Apache user if mod_php is being used)
$config['temp_dir'] = '/tmp/roundcube-temp';

// tell PHP that it should work as under secure connection
// even if it doesn't recognize it as secure ($_SERVER['HTTPS'] is not set)
// e.g. when you're running Roundcube behind a https proxy
// this option is mutually exclusive to 'force_https' and only either one of them should be set to true.
$config['use_https'] = true;

// This key is used for encrypting purposes, like storing of imap password
// in the session. For historical reasons it's called DES_key, but it's used
// with any configured cipher_method (see below).
// For the default cipher_method a required key length is 24 characters.
$config['des_key'] = 'w/oqBVSPeUrUbLdSnHX7zvyg';

// Specifies the full path of the original HTTP request, either as a real path or
// $_SERVER field name. This might be useful when Roundcube runs behind a reverse
// proxy using a subpath. This is a path part of the URL, not the full URL!
// The reverse proxy config can specify a custom header (e.g. X-Forwarded-Path) containing
// the path under which Roundcube is exposed to the outside world (e.g. /rcube/).
// This header value is then available in PHP with $_SERVER['HTTP_X_FORWARDED_PATH'].
// By default the path comes from  'REDIRECT_SCRIPT_URL', 'SCRIPT_NAME' or 'REQUEST_URI',
// whichever is set (in this order).
$config['request_path'] = '/';

// ----------------------------------
// PLUGINS
// ----------------------------------
// List of active plugins (in plugins/ directory)
$config['plugins'] = ['archive', 'zipdownload', 'markasjunk'];

// Make use of the built-in spell checker.
$config['enable_spellcheck'] = true;

// Set the spell checking engine. Possible values:
// - 'googie'  - the default (also used for connecting to Nox Spell Server, see 'spellcheck_uri' setting)
// - 'pspell'  - requires the PHP Pspell module and aspell installed
// - 'enchant' - requires the PHP Enchant module
// - 'atd'     - install your own After the Deadline server or check with the people at https://www.afterthedeadline.com before using their API
// Since Google shut down their public spell checking service, the default settings
// connect to https://spell.roundcube.net which is a hosted service provided by Roundcube.
// You can connect to any other googie-compliant service by setting 'spellcheck_uri' accordingly.
$config['spellcheck_engine'] = 'pspell';

$config['zipdownload_selection'] = true;

// Tinh chỉnh Trải nghiệm người dùng (Giảm lag UI)
$config['mail_pagesize'] = 30; // Rút xuống 30 thư/trang để tải danh sách nhanh hơn
$config['display_next'] = false; // Tắt tự động mở thư tiếp theo khi xóa thư cũ
$config['refresh_interval'] = 120; // Giảm tần suất tự động check mail mới (giảm tải cho MariaDB)

// USCC branding (see DESIGN_SYSTEM.md). Logo paths are resolved inside the skin
// directory (served through static.php), so the files are installed into
// skins/elastic/images/ by skin-overrides/install.sh. Only enable once they exist,
// otherwise the login page would show a broken image.
if (is_file(__DIR__ . '/../skins/elastic/images/uscc_login.png')) {
    $config['skin_logo'] = [
        'elastic:*'             => '/images/uscc_login.png',
        'elastic:*[small]'      => '/images/uscc_small.png',
        'elastic:*[dark]'       => '/images/uscc_dark.svg',
        'elastic:*[small-dark]' => '/images/uscc_dark.svg',
    ];
}

include(__DIR__ . '/config.docker.inc.php');
