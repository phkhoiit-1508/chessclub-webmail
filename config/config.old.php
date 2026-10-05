<?php

    $config['imap_cache'] = 'db';
    $config['messages_cache'] = true;

    
    $config['imap_cache_ttl'] = '10d';
    $config['messages_cache_ttl'] = '10d';

    
    $config['keep_alive'] = 60;

    
    $config['imap_timeout'] = 15;
    $config['smtp_timeout'] = 15;

    
    $config['use_https'] = true;
    $config['enable_caching'] = true;
    $config['plugins'] = [];
    $config['log_driver'] = 'stdout';
    $config['zipdownload_selection'] = true;
    $config['des_key'] = 'w/oqBVSPeUrUbLdSnHX7zvyg';
    $config['enable_spellcheck'] = true;
    $config['spellcheck_engine'] = 'pspell';
    include(__DIR__ . '/config.docker.inc.php');
    
