<?php

namespace AppBundle;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use \Tsugi\Core\Settings;
use \Tsugi\Util\Net;

class WelcomeLR {

    public function get(Request $request, Application $app)
	    {
        global $CFG, $PDOX;

	$context = array();

	    $providers = $app['tsugi']->context->launch->ltiRawParameter('lis_course_section_sourcedid','none');
        $context_id = $app['tsugi']->context->launch->ltiRawParameter('context_id','none');

    	$path = $CFG->getPWD('index.php');
    	$context['style'] = $CFG->wwwroot .'/'. $path .'/static/user.css';
	    $context['submit'] = addSession($CFG->wwwroot .'/'. $path .'/index.php');
        $context['providers'] = array();
        $context['provider'] = 'none';
        
        if ($providers != $context_id) {
            // So we might have some providers to show
                $list = explode('+', $providers);
                
            if (count($list) == 1) {
                $context['provider'] = $list[0];
            } else {
                $context['providers'] = $list;
            }
        }

        $context['path'] = $CFG->staticroot;
        $context['course_title'] = $app['tsugi']->context->title;
        $context['email'] = $app['tsugi']->user->email;
        $context['user'] = $app['tsugi']->user->displayname;
        $context['username'] = $app['tsugi']->context->launch->ltiRawParameter('lis_person_sourcedid', $app['tsugi']->user->id);         

        return $app['twig']->render('WelcomeLR.twig', $context);
    }

    public function post(Request $request, Application $app)
    {
        global $CFG, $PDOX;

        $result = array_merge(array( 
            'ext_sakai_server' => $app['tsugi']->context->launch->ltiRawParameter('ext_sakai_server','none')
            ,'instructor' => $app['tsugi']->user->instructor
            ,'siteid' => $app['tsugi']->context->launch->ltiRawParameter('context_id','none')
            ,'ownerEid' => $app['tsugi']->context->launch->ltiRawParameter('lis_person_sourcedid','none') 
            ,'ownerEmail' => $app['tsugi']->user->email
            ,'organizer' => $app['tsugi']->user->displayname
            ,'language' => 'eng'
            ,'title' => $app['tsugi']->context->title
            ,'description' => $app['tsugi']->context->title
            ,'publisher' => 'University of Cape Town'
            ,'done' => 0
            ,'msg'  => 'Application failure.'
        ), $_POST);

        if ($result['course'] == 'none') {
            $result['course'] = '';
        }

        $out = array(
            'done' => 0
            ,'msg'  => 'Application failure.'
        );

        if ($app['tsugi']->user->instructor) {

            $cmd = NULL;
            $filename = $CFG->dirroot ."/". $CFG->getPWD('index.php') ."/tmp/". $result['siteid'] .".json";
            $fp = fopen($filename, 'w');
            fwrite($fp, json_encode($result));
            fclose($fp);

            switch ($result['type']) {
               case "create":
                    $cmd = 'sudo /usr/local/sakaiscripts/jira/tsugi-oc-setup.pl '. $filename;
                    break;
                case "remove":
                    $cmd = 'sudo /usr/local/sakaiscripts/jira/tsugi-oc-remove.pl '. $result['ext_sakai_server'] .' '. $result['siteid'];
                default: break;
            }

            if (!is_null($cmd)) {
                $result['cmd'] = $cmd; 
                $result['raw'] = shell_exec($cmd);
                $result['out'] = json_decode($result['raw']);

                if (json_last_error() === JSON_ERROR_NONE) { 
                    $out['msg'] = $result['out']->out;
                    $out['done'] = $result['out']->success;
                } else { 
                    $out['done'] = 0;
                    $out['msg'] = json_last_error_msg();
                } 
            }

            $filename = $CFG->dirroot ."/". $CFG->getPWD('index.php') ."/tmp/". $result['siteid'] .".json";
            $fp = fopen($filename, 'w');
            fwrite($fp, json_encode($result));
            fclose($fp);
        } else {
            $out['done'] = 0;
            $out['msg']  = 'Must be an instructor to complete this operation.';
        }

        return json_encode($out);
    }
}
