<?php
date_default_timezone_set('PRC');

define('BASE_PATH', dirname(__dir__));

define('APP_ROOT', BASE_PATH . '/app');

require BASE_PATH . '/vendor/autoload.php';

use Hyperf\Utils\ApplicationContext;

$container = \Gene\Factory::create(\sf\Container::class);

ApplicationContext::setContainer($container);

$app = \Gene\Application::getInstance();
$app->autoload(APP_ROOT)
    ->load("router.ini.php")
    ->load("config.ini.php");

(new \sf\swoole\Swf($app::config('server'), $app))->run($argv);

