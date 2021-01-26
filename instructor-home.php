<?php
require_once('../config.php');
include 'tool-config.php';

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$menu = false; // We are not using a menu

// Start of the output
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$OUTPUT->topNav($menu);

if (!$USER->instructor) {
    header('Location: ' . addSession('student-home.php'));
}

    $context = array();
    $providers  = $LAUNCH->ltiRawParameter('lis_course_section_sourcedid','none');
    $context_id = $LAUNCH->ltiRawParameter('context_id','none');

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
    
    // $context['course_title'] = $app['tsugi']->context->title;
    $context['email'] = $USER->email;
    $context['user'] = $USER->displayname;
    $context['submit'] = addSession( str_replace("\\","/",$CFG->getCurrentFileUrl('process.php')) );
    
    if ($tool['debug']) {
        echo '<pre>'; print_r($context); echo '</pre>';
    }
?>
    <section>
        <div class="row">
            <div class="col-xs-12">
                <h3>Set up a new recording series</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>If you'd like your lectures recorded:</p>
                <ol>
                <li>Complete the form below.</li>
                <li>Click the checkbox to accept the responsibilities of a series organizer.</li>
                <li>Click the <strong>Get Started</strong> button.</li>
                <li>Create and manage the recording schedule.</li>
                </ol>
                <p>
                    Need more info <span class="glyphicon glyphicon-question-sign"></span>
                    <a href="https://vula.uct.ac.za/access/content/public/help/HowTo_SetupLectureRecording.pdf" title="How to set up my lecture recordings" target="_blank">Setup guide</a>,
                    <a href="http://ictsapps.uct.ac.za/lectureRecording.php" target="_blank" title="Venues equipped for lecture recording">venue information</a>,
                    <a href="http://www.cilt.uct.ac.za/cilt/lecture-recording/" target="_blank" title="More about lecture recording at UCT">lecture recording at UCT</a>.
                </p>
            </div>
            <div class="col-md-4 col-md-offset-1 bg-info" style="padding: 1em; margin-top: 1em;">
                <p>If you don't want to record lectures for this course, click the <strong>No Thanks</strong> button to remove this page.</p>
            </div>
        </div>
    </section>
    <section>
        <div class="row">
            <div class="col-md-9">
                <form class="form-inline text-left" method="post" target="_self" id="metadata">
                    <input type="hidden" name="type"  id="type" value="remove"/>
		
                    <!--div class="row">
                        <div class="col-md-3 text-right hidden-sm hidden-xs">
                            <h4>Series Information:</h4>
                        </div>
                        <div class="col-sm-12 hidden-md hidden-lg">
                            <h4>Series Information:</h4>
                        </div>
                    </div-->

		            <div class="row" style="margin-top: 1em;">
                        <div class="col-md-3 hidden-sm hidden-xs">
                            <label>Series Organizer</label>
                        </div>
                        <div class="col-xs-12 col-sm-11 hidden-md hidden-lg">
                            <label>Series Organizer</label>
                        </div>
                        <div class="col-md-8 col-xs-12 col-sm-11 col-md-offset-0">
                            <input type="text" name="organizer" id="organizer" disabled="true" class="form-control disabled" value="<?= $context['email'] ?> (<?= $context['user'] ?>)"/>
                        </div>
                    </div>
                <?php

                    if (count($context['providers']) > 1) {
                ?>
                        <div class="row">
                            <div class="col-md-3 hidden-sm hidden-xs">
                                <label for="presenters">Primary Course</label>
                            </div>
                            <div class="col-xs-12 col-sm-11 hidden-md hidden-lg">
                                <label for="presenters">Primary Course</label>
                            </div>
                            <div class="col-md-8 col-xs-12 col-sm-11 col-md-offset-0">
                                <select class="form-control" name="provider" id="provider">
                                <?php
                                    foreach ($context['providers'] as $p) {
                                        print "<option value=\"". $p ."\">". $p ."</option>";
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                <?php
                    } else { 
                        print "<input type=\"hidden\" name=\"provider\" id=\"provider\" value=\"". $context['provider'] ."\"/>";
                    }
                ?>
                    <div class="row">
                        <div class="col-md-3 hidden-sm hidden-xs">
                            <label>Publish recordings to</label>
                        </div>
                        <div class="col-xs-12 col-sm-11 hidden-md hidden-lg">
                            <label>Publish recordings to</label>
                        </div>
                        <div class="col-md-8 col-xs-12 col-sm-11 col-md-offset-0">
                            <label class="radio-inline">
                                <input type="radio" name="visibility" id="courseClosed" value="closed" checked>
                                This Vula site only
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="visibility" id="coursePublic" value="public">
                                Anyone (public)
                            </label>
                        </div>
                    </div>
                    <!--div class="row">
                        <div class="col-md-3 hidden-sm hidden-xs">
                            <label for="subject">Subject</label>
                        </div>
                        <div class="col-xs-12 col-sm-11 hidden-md hidden-lg">
                            <label for="subject">Subject</label>
                        </div>
                        <div class="col-md-8 col-xs-12 col-sm-11 col-md-offset-0">
                            <input type="text" class="form-control" name="subject" id="subject" value=""/>
                        </div>
                    </div-->
                    <div class="row">
                        <div class="col-md-3 hidden-sm hidden-xs">
                            <label for="presenters">Presenter(s)</label>
                        </div>
                        <div class="col-xs-12 col-sm-11 hidden-md hidden-lg">
                            <label for="presenters">Presenter(s)</label>
                        </div>
                        <div class="col-md-8 col-xs-12 col-sm-11 col-md-offset-0">
                            <textarea class="form-control" name="presenters" id="presenters" placeholder="Presenters for this course, one per line."><?= $context['user'] ?>
                            </textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 hidden-sm hidden-xs">
                            <label for="presenters">Email Notifications</label>
                        </div>
                        <div class="col-xs-12 col-sm-11 hidden-md hidden-lg">
                            <label for="presenters">Email Notifications</label>
                        </div>
                        <div class="col-md-8 col-xs-12 col-sm-11 col-md-offset-0">
                            <textarea class="form-control" name="notifications" id="notifications" placeholder="Additional email addresses to receive scheduling notifications for this series"></textarea>
                        </div>
                    </div>
                    <div class="row terms">
                        <div class="col-md-3 hidden-sm hidden-xs">
                            <label>My Responsibilities</label>
                        </div>
                        <div class="col-xs-12 col-sm-11 hidden-md hidden-lg">
                            <label>My Responsibilities</label>
                        </div>
                        <div class="col-md-8 col-xs-12 col-sm-11 col-md-offset-0">
                            <label class="checkbox-inline">
                                <input type="checkbox" id="terms" name="terms" value="accept" /> 
                                As the Series Organizer, I undertake to: (1) <a href="http://www.cilt.uct.ac.za/cilt/lecture-recording" target="_blank">obtain recording consent</a> in advance from all presenters for all scheduled recordings, and (2) inform students and other participants that sessions are recorded.
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button id="btnAccept" class="btn btn-success disabled" disabled="true" type="button">
                                <i class="fa fa-check"></i>
                                Get Started
                            </button>
                            <button id="btnReject" class="btn btn-default" type="button">
                                No Thanks
                            </button>
                            <span id="info" class="text-info" style="display:none;"><small>This might take a couple of seconds.</small></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12" id="message"></div>
                    </div>
                </form>
            </div>
        </div>
    </section>
<?php

$OUTPUT->footerStart();

?>
<!-- Our main javascript file for tool functions -->
<script>
    $(function() {
        var timeout = null;

        function hideHelp() {
            clearTimeout(timeout);
            $('#info').hide();
        }
        function showError(a) {
            $('#' + a).html('<i class="fa fa-exclamation"></i> Error').addClass('disabled').attr('disabled', true);
            $('#message').html('<p class="bg-danger">An error occurred while performing this action, please contact <a href="mailto:help@vula.uct.ac.za?subject=Vula - Please help with: Lecture Recording Setup">help@vula.uct.ac.za</a><br/> or call 021-650-5500 weekdays 8:30 - 17:00.</p>');
        }
        function doPost(a, b, text, type) {
            $('#' + a).html('<i class="fa fa-cog fa-spin"></i>' + text);
            $('#' + b).addClass('disabled').attr('disabled', true).hide();
            timeout = setTimeout(function(){ $('#info').show(); }, 1200);

            var contributor = $('#presenters').val().trim().replace(/\r?\n/g, ', ');
            var notification = $('#notifications').val().trim().replace(/\r?\n/g, ', ');

            var data = { 
                "type": type,
                "terms": ($('#terms').is(':checked') ? "accept" : "rejected"),
                "visibility": ($('#coursePublic').is(':checked') ? "Public" : "Vula site only"),
                "subject": '',
                "contributor": (contributor.endsWith(', ') ? contributor.substring(0, contributor.length-2) : contributor),
                "course": $('#provider').val(),
                "notification": (notification.endsWith(', ') ? notification.substring(0, notification.length-2) : notification)
            }

            var jqxhr = $.post('<?= $context['submit'] ?>', data, function(result) {
                hideHelp();
                console.log(result['done'] +' '+ (result['done'] === 1));
                if (result['done'] === 1) {
                    $('#' + a).html('<i class="fa fa-check"></i> Refreshing page ...');

                    // post refresh
                    parent.postMessage(JSON.stringify({ subject: "lti.pageRefresh" }), "*");
                } else {
                    showError(a);
                }
            }, 'json')
            .fail(function() {
                hideHelp();
                showError(a);
            })
            .always(function() {
                hideHelp();
            });
        }

        $('#terms').click( function(){
            if( $(this).is(':checked')) {
                $('#btnAccept').removeClass("disabled").attr("disabled", false);
            } else {
                $('#btnAccept').addClass("disabled").attr("disabled", true);
            }   
        });
        $('#btnAccept').click( function(event){
            event.preventDefault();
            doPost('btnAccept', 'btnReject', 'Setting up lecture recording...', 'create');
        });
        $('#btnReject').click( function(event){
            event.preventDefault();
            doPost('btnReject', 'btnAccept', 'Removing lecture recording...', 'remove');
        });
    });
</script>
<?php

$OUTPUT->footerEnd();