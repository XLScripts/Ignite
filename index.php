<?php 

$s = microtime(TRUE);

/* Important constants to be used by the application */
define('BASE_PATH',    __DIR__ . '/');
define('PUBLIC_PATH',  __DIR__ . '/');
define('APP_PATH',     BASE_PATH . 'App/');
define('CFG_PATH',     APP_PATH . 'Config/');
define('STORAGE_PATH', BASE_PATH . 'storage/');
define('BACKEND_PATH', STORAGE_PATH . 'backend/');
define('CONTENT_PATH', STORAGE_PATH . 'app/');

require_once(BASE_PATH . 'vendor/autoload.php');
require_once(APP_PATH . 'App.php');

$Application = new \Ignite\App();

$Application->run();

$e = microtime(TRUE);

?>

<script>
    console.log('<?php echo $e - $s ?>ms');
</script>