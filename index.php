<?php
require_once "../config.php";

use \Tsugi\Core\Settings;
use \Tsugi\Core\LTIX;

$launch = LTIX::requireData();
$app = new \Tsugi\Silex\Application($launch);
$app['debug'] = true;

#$ocApi = new OCAPI;
#var_dump($ocApi->createSeries("someCourse", ["title"=>"some other title"]));

//var_dump($launch->context);

//print $launch->ltiRawParameter('context_id','empty');
//print $launch->ltiRawParameter('lis_course_section_sourcedid','none');

$app->get('/', 'AppBundle\\WelcomeLR::get')->bind('main');
$app->post('/', 'AppBundle\\WelcomeLR::post');

$app->run();


