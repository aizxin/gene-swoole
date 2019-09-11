<?php
/**
 * FileName: index.php
 * ==============================================
 * Copy right 2016-2017
 * ----------------------------------------------
 * This is not a free software, without any authorization is not allowed to use and spread.
 * ==============================================
 * @author: kong | <iwhero@yeah.com>
 * @date  : 2019-08-24 23:17
 */

define('APP_ROOT', dirname(__dir__) . '/application');

function geneHandler($e)
{
   var_dump($e);
}

$app = \Gene\Application::getInstance();
$app->autoload(APP_ROOT)
   ->load("router.ini.php")
   ->load("config.ini.php")
   ->setMode(1, 1)
   ->run();