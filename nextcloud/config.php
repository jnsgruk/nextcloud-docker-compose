<?php
/*
*
* This is an example config for Nextcloud showing how to connect up the
* Redis instance for file locking and use the S3 backend for storage
*
*/
 
$CONFIG = array (
  'htaccess.RewriteBase' => '/',
  'filelocking.enabled' => true,
  'memcache.local' => '\\OC\\Memcache\\APCu',
  'memcache.distributed' => '\\OC\\Memcache\\Redis',
  'memcache.locking' => '\\OC\\Memcache\\Redis',
  'redis' => 
  array (
    'host' => 'nc-redis',
    'port' => 6379,
    'timeout' => 0.0,
    'password' => 'SUPERSECRETPASSWORD',
  ),
  'objectstore' => 
  array (
    'class' => '\\OC\\Files\\ObjectStore\\S3',
    'arguments' => 
    array (
      'bucket' => 'BUCKETNAME',
      'autocreate' => true,
      'key' => 'SUPERSECRETKEY',
      'secret' => 'SECRETKEY',
      'hostname' => 's3.eu-central-1.wasabisys.com',
      'region' => 'eu-central-1',
      'port' => 443,
      'use_ssl' => true,
      'use_path_style' => true,
    ),
  ),
  'trusted_domains' => 
  array (
    0 => 'cloud.somedomain.com',
  ),
  'trusted_proxies' => 
  array (
    0 => '172.19.0.2',
  ),
  'apps_paths' => 
  array (
    0 => 
    array (
      'path' => '/var/www/html/apps',
      'url' => '/apps',
      'writable' => false,
    ),
    1 => 
    array (
      'path' => '/var/www/html/custom_apps',
      'url' => '/custom_apps',
      'writable' => true,
    ),
  ),
  'overwrite.cli.url' => 'https://cloud.somedomain.com',
  'overwriteprotocol' => 'https',
);