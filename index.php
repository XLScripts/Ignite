<?php 
/* - Define various constants used by the application
-- BASE_PATH    = Path to the Project's root directory
-- PUBLIC_PATH  = Path to the public/static directory containing index.php
-- APP_PATH     = Path to the Source Directory of the application
-- STORE_PATH   = Path to the directory used for storing non-public files.
-- CONTENT_PATH = Path to the directory containing public files by the CMS. Should be under a PUBLIC_PATH
*/

define('BASE_PATH',    __DIR__ . '/');
define('PUBLIC_PATH',  __DIR__ . '/');
define('APP_PATH',     BASE_PATH . 'App/');
define('CFG_PATH',     APP_PATH . 'Config/');
define('STORAGE_PATH',   BASE_PATH . 'storage/');
define('CONTENT_PATH', STORAGE_PATH . 'app/');

require_once(BASE_PATH . 'vendor/autoload.php');
require_once(APP_PATH . 'App.php');

$Application = new \Ignition\App();

$Application->run();