<?php
  $config['db_dsnw'] = 'mysql://roundcube:roundcube_pass@roundcubedb:3306/roundcubemail';
  $config['db_dsnr'] = '';
  $config['imap_host'] = 'ssl://imap.gmail.com:993';
  $config['smtp_host'] = 'ssl://smtp.gmail.com:465';
  $config['username_domain'] = '';
  $config['temp_dir'] = '/tmp/roundcube-temp';
  $config['skin'] = 'elastic';
  $config['request_path'] = '/';
  $config['plugins'] = array_filter(array_unique(array_merge($config['plugins'], ['archive', 'zipdownload'])));
  
