<?php
/**
 * Where on the filesystem this application is installed
 */
define('APPLICATION_HOME', realpath(__DIR__.'/../../..'));
define('VERSION', trim(file_get_contents(APPLICATION_HOME.'/VERSION')));

define('SITE_HOME', __DIR__);
include SITE_HOME.'/test_config.inc';

$loader = require APPLICATION_HOME.'/vendor/autoload.php';
include SITE_HOME.'/container.php';
include 'src/Web/routes.php';
include 'src/Web/access_control.php';
