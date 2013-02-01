<?php

###########################################################
# Reinit
###########################################################
require_once SCRIPT_DIR_PATH . '/S3.php';

log_message("\033[32mRemoving all files in the S3 bucket...\033[37m");

$s3 = new S3(S3_ACCESS_KEY, S3_SECRET_KEY);  

$contents = $s3->getBucket(S3_BUCKET_NAME);
	
foreach ($contents as $file)
{
	$msg = '';

    if ($s3->deleteObject(S3_BUCKET_NAME, $file['name'])) 
    {  
        $msg = '\033[32m' . $file['name'] . ' was successfully deleted.\033[37m';
    }
    else
    {  
        $errorHappened = TRUE;
        $msg = '\033[32m' . $file['name'] . ' was NOT deleted.\033[37m';
    }
    log_message($msg);
}

log_message("\033[32mDone.\033[37m");