<?php

$REGISTER_LTI2 = array(
    "name" => "Welcome to Lecture Recording"
    ,"FontAwesome" => "fa-video-camera"
    ,"short_name" => "Welcome to Lecture Recording"
    ,"description" => "Shows a page to introduce lecture recording to a course site and help with the initial configuration."
    ,"messages" => array("launch") // By default, accept launch messages..
    ,"privacy_level" => "public" // anonymous, name_only, public
    ,"license" => "Apache"
    ,"languages" => array(
        "English",
    )
    ,"source_url" => "https://github.com/cilt-uct/tsugi-welcome-to-lecture-recording"
    // For now Tsugi tools delegate this to /lti/store
    ,"placements" => array(
        /*
        "course_navigation", "homework_submission",
        "course_home_submission", "editor_button",
        "link_selection", "migration_selection", "resource_selection",
        "tool_configuration", "user_navigation"
        */
    )
    ,"screen_shots" => array(
        /* no screenshots */
    )
);
