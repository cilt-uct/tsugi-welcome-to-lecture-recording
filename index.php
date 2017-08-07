<?php
require_once "../config.php";

use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;

$launch = LTIX::requireData();
$app = new \Tsugi\Silex\Application($launch);
$app['debug'] = true;

$app->get('/', 'AppBundle\\WelcomeLR::get')->bind('main');
$app->post('/', 'AppBundle\\WelcomeLR::post');

$app->run();


