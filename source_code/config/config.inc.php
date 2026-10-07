<?php

/* Local configuration for Roundcube Webmail */

// ----------------------------------
// SQL DATABASE
// ----------------------------------
$config['db_dsnw'] = 'mysql://roundcube:roundcube_pass@roundcubedb:3306/roundcubemail';

// ----------------------------------
// LOGGING/DEBUGGING
// ----------------------------------
$config['log_driver'] = 'stdout';

// ----------------------------------
// IMAP
// ----------------------------------
$config['imap_host'] = 'ssl://imap.gmail.com:993';
$config['imap_timeout'] = 15;
$config['messages_cache'] = true;

// ----------------------------------
// SMTP
// ----------------------------------
$config['smtp_host'] = 'ssl://smtp.gmail.com:465';
$config['smtp_conn_options'] = [
    'ssl' => [
        'verify_peer'       => true,
        'verify_peer_name'  => true,
        'bindto'            => '0.0.0.0:0' // Ép IPv4 để tránh timeout DNS
    ],
];
$config['smtp_timeout'] = 15;

// ----------------------------------
// SYSTEM & SECURITY
// ----------------------------------
$config['support_url'] = '';
$config['temp_dir'] = '/tmp/roundcube-temp';
$config['use_https'] = true;
$config['des_key'] = 'w/oqBVSPeUrUbLdSnHX7zvyg';
$config['request_path'] = '/';

// ----------------------------------
// PLUGINS & SPELLCHECK
// ----------------------------------
$config['plugins'] = ['archive', 'zipdownload', 'markasjunk'];
$config['enable_spellcheck'] = true;
$config['spellcheck_engine'] = 'pspell';
$config['zipdownload_selection'] = true;

// ----------------------------------
// UI & BRANDING
// ----------------------------------
$config['mail_pagesize'] = 30;
$config['display_next'] = false;
$config['refresh_interval'] = 120;

if (is_file(__DIR__ . '/../skins/elastic/images/uscc_login.png')) {
    $config['skin_logo'] = [
        'elastic:*'             => '/images/uscc_login.png',
        'elastic:*[small]'      => '/images/uscc_small.png',
        'elastic:*[dark]'       => '/images/uscc_dark.svg',
        'elastic:*[small-dark]' => '/images/uscc_dark.svg',
    ];
}

// ----------------------------------
// --- TỐI ƯU HIỆU NĂNG (REDIS & IMAP) ---
// ----------------------------------
// 1. Redis Session & IMAP Cache
$config['redis_hosts'] = ['redis:6379'];
$config['session_storage'] = 'redis';
$config['imap_cache'] = 'redis';
$config['imap_cache_ttl'] = '10d';

// 2. Messages Cache
$config['messages_cache_ttl'] = '10d';
$config['messages_cache_threshold'] = 500;
$config['messages_prefetch'] = true;

// 3. Ép tải thư nhanh, bỏ qua fetch header (Chống lag thư mục lớn)
$config['message_sort_col'] = '';
$config['message_sort_order'] = 'DESC';
$config['dont_override'] = ['message_sort_col', 'message_sort_order'];

// 4. Tối ưu tìm kiếm và tác vụ ngầm
$config['search_scope'] = 'base';
$config['search_mods'] = [
    '*' => ['subject' => 1, 'from' => 1], 
    'Sent' => ['subject' => 1, 'to' => 1], 
    'Drafts' => ['subject' => 1, 'to' => 1]
];
$config['draft_autosave'] = 300;
$config['autocomplete_min_length'] = 3;
$config['check_all_folders'] = false;
$config['skip_deleted'] = true;
$config['use_minified_assets'] = true;

// ----------------------------------
// --- TỐI ƯU HÓA TỐC ĐỘ ĐĂNG NHẬP (IMAP HANDSHAKE) ---
// ----------------------------------
// 1. Ép sử dụng phương thức đăng nhập PLAIN ngay lập tức (Bỏ qua bước dò hỏi Gmail)
$config['imap_auth_type'] = 'PLAIN';

// 2. Ép sử dụng Namespace mặc định
$config['imap_force_ns'] = true;

// 3. Giảm thời gian chờ Timeout của Socket & Ép IPv4
$config['imap_conn_options'] = [
    'ssl' => [
        'verify_peer'       => true,
        'verify_peer_name'  => true,
        'bindto'            => '0.0.0.0:0' 
    ],
];

include(__DIR__ . '/config.docker.inc.php');