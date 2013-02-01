<?php

###########################################################
# Notify
###########################################################

log_message("\033[32mSending notifications...\033[37m");

$fullUrlPath = 'http://' . S3_CLOUD_FRONT . '/' . $ipaFileName;
$iconUrlPath = 'http://' . S3_CLOUD_FRONT . '/' . 'icon.png';
$plistUrlPath = 'http://' . S3_CLOUD_FRONT . '/' . $plistFileName;

$to = EMAIL_SEND_TO;

//define the subject of the email
$subject = 'New build v.' . $bundleVersion . ' notification'; 
//create a boundary string. It must be unique 
//so we use the MD5 algorithm to generate a random hash
$random_hash = md5(date('r', time())); 
//define the headers we want passed. Note that they are separated with \r\n
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

// Additional headers
$headers .= 'From: ' . EMAIL_SEND_FROM;
//define the body of the message.
$message = '   

<h2>Build v.' . $bundleVersion . ' is ready to download</h2>

<p>
Tap on <b>Bubbly</b> icon to install ' . $ipaFileName . '
</p>

<p>
<a href=itms-services://?action=download-manifest&url=' . $plistUrlPath . '>
        <img src=' . $iconUrlPath . ' height="57" width="57" />
</a>
</p>

<p>
<a href=' . $fullUrlPath . '>
  Direct link to download ' . $ipaFileName . '
</a>
</p>

<p>Good luck!</p>';

//send the email
$mail_sent = @mail( $to, $subject, $message, $headers );
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 

if ($mail_sent)
{
    log_message("\033[32mMail sent.\033[37m");
}
else
{
    $errorHappened = TRUE;
    log_message("\033[32mMail failed.\033[37m");
}

log_message("\033[32mDone.\033[37m");