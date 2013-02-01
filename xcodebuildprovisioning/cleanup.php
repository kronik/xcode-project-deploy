<?php

###########################################################
# Cleanup
###########################################################

echo 'Do you want to clean the build folder? YES / NO (default YES): ';
$cleanupChoice = trim(fgets(STDIN));
	
if ($cleanupChoice === 'n' || $cleanupChoice === 'N' || $cleanupChoice === 'NO' || $cleanupChoice === 'no')
{
    echo 'Cleanup was canceled';
}
else
{
    log_message("\033[32mCleanup...\033[37m");

    $rmCmd = 'rm -rf "'. $logDirPath . '"';

    echo $rmCmd;
    if (file_exists($logDirPath)) exec($rmCmd);

    $rmCmd = 'rm -rf "'. $buildDirPath . '"';

    echo $rmCmd;
    if (file_exists($buildDirPath)) exec($rmCmd);
    if (file_exists($plistFilePath)) unlink($plistFilePath);
}

log_message("\033[32mDone.\033[37m");