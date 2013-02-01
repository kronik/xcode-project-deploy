#!/usr/bin/php
<?php

// Set your TimeZone here
date_default_timezone_set('Asia/Singapore');

// Paths
define('SCRIPT_DIR_PATH', './xcodebuildprovisioning');

// Xcode stuff
// If left commented script will prompt you for input
// Useful when using with CI
// define('DEVELOPER_IDENTITY', '');
// define('PROV_PROFILE_PATH', '');

define('TARGET_SDK', 'iphoneos');
define('BUILD_CONFIGURATION', 'Test - Debug');
define('BUILD_SCHEME', 'ProjectName (Test)');

// TestFlightApp stuff
define('TESTFLIGHT_API_TOKEN', 'YOUR_TESTFLIGHT_API_TOKEN_HERE');
define('TESTFLIGHT_TEAM_TOKEN', 'YOUR_TESTFLIGHT_TEAM_TOKEN_HERE');
define('TESTFLIGHT_DISTRIBUTION_LISTS', 'YOUR_TESTFLIGHT_EMAIL_LIST_HERE');

define('S3_BUCKET_NAME', 'YOUR_S3_BUCKET_HERE');
define('S3_CLOUD_FRONT', 'YOUR_CLOUD_FRONT_ACCESS_URL_HERE');
define('S3_SECRET_KEY', 'YOUR_S3_SECRET_HERE');
define('S3_ACCESS_KEY', 'YOUR_S3_ACCESS_KEY_HERE');

define('EMAIL_SEND_FROM', 'EMAIL_SENT_FROM_HERE');
define('EMAIL_SEND_TO', 'EMAIL_NOTIFICATION_LIST_HERE');

$errorHappened = FALSE;

###########################################################
# Launch
###########################################################
#require_once(SCRIPT_DIR_PATH . '/reinit.php'); // Removes all files in the S3 bucket

if ($errorHappened == FALSE)
{
    require_once(SCRIPT_DIR_PATH . '/build.php');
}

if ($errorHappened == FALSE)
{
    require_once(SCRIPT_DIR_PATH . '/deploy.php');
}

if ($errorHappened == FALSE)
{
    require_once(SCRIPT_DIR_PATH . '/notify.php');
}

if ($errorHappened == FALSE)
{
    require_once(SCRIPT_DIR_PATH . '/cleanup.php');
}