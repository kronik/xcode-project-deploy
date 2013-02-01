<?php

$errorHappened = FALSE;

// Building and signing is done in xcodebuildprovisioning script
require_once SCRIPT_DIR_PATH . '/build.php';
require_once SCRIPT_DIR_PATH . '/S3.php';

###########################################################
# Upload to TestFlightApp / S3
###########################################################

log_message("\033[32mTest build deploy...\033[37m");

log_message("\033[32mSending " . $ipaPath . " to S3... \033[37m");

$s3 = new S3(S3_ACCESS_KEY, S3_SECRET_KEY);  

if ($s3->putObjectFile($ipaPath, S3_BUCKET_NAME, $ipaFileName, S3::ACL_PUBLIC_READ)) 
{  
    log_message("\033[32mWe successfully uploaded your build.\033[37m");

    if ($s3->putObjectFile(SCRIPT_DIR_PATH . '/icon.png', S3_BUCKET_NAME, 'icon.png', S3::ACL_PUBLIC_READ)) 
    {  
        log_message("\033[32mWe successfully uploaded your build icon.\033[37m");
    }
    else
    {  
        log_message("\033[32mSomething went wrong while uploading your build icon.\033[37m");
    }

    $plistFileName = 'Project' . date('Y.m.d-H.i.s') . '.plist';
    $plistFilePath = SCRIPT_DIR_PATH . '/' . $plistFileName;

    $file = @file_get_contents(SCRIPT_DIR_PATH . '/Project.plist.tpl');

    $fullUrlPath = 'http://' . S3_CLOUD_FRONT . '/' . $ipaFileName;

    if($file) 
    {
        $file = str_replace('[IPA_URL]', 'http://' . S3_CLOUD_FRONT . '/' . $ipaFileName, $file);
        $file = str_replace('[IPA_VER]', $ipaFileName, $file);

        $fp = fopen($plistFilePath, 'w');
        fwrite($fp, $file, strlen($file));
        fclose($fp);
    }
    else
    {
        $errorHappened = TRUE;
    }

    if ($s3->putObjectFile($plistFilePath, S3_BUCKET_NAME, $plistFileName, S3::ACL_PUBLIC_READ)) 
    {  
        log_message("\033[32mWe successfully uploaded your build manifest.\033[37m");
    }
    else
    {  
        $errorHappened = TRUE;
        log_message("\033[32mSomething went wrong while uploading your build manifest.\033[37m");
    }

}
else
{  
    $errorHappened = TRUE;
    log_message("\033[32mSomething went wrong while uploading your build.\033[37m");
}

log_message("\033[32mDone.\033[37m");

log_message("\033[32mSending " . $ipaPath . " to TESTFLIGHT... \033[37m");

$notify = 'false';

if (defined(TESTFLIGHT_DISTRIBUTION_LISTS)) {
	while ($notify != 'y' && $notify != 'n') {
		echo 'Notify testers from ' . TESTFLIGHT_DISTRIBUTION_LISTS . '? y/n: ';
		$notify = trim(fgets(STDIN));
	}
	$notify = $notify == 'y' ? 'true' : 'false';
}

$command = 'curl http://testflightapp.com/api/builds.json --progress-bar -F file="@' . $ipaPath . '" -F api_token="' . TESTFLIGHT_API_TOKEN . '" -F team_token="' . TESTFLIGHT_TEAM_TOKEN . '" -F notes="' . $buildNotes . '" -F notify=' . $notify . ' -F distribution_lists="' . TESTFLIGHT_DISTRIBUTION_LISTS . '" >> "' . $logPath . '"';
exec($command);

log_message("\033[32mDone.\033[37m");