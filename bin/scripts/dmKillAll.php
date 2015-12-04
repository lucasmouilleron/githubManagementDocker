<?php

///////////////////////////////////////////////////////////////////////////////
$runningProjects = $PM->getRunningProjects();
appendToLog(LG_MAIN, LG_INFO, "# running projects", count($runningProjects));

///////////////////////////////////////////////////////////////////////////////
foreach ($runningProjects as $runningProject) {
    $projectName = $runningProject->projectInfos->name;
    appendToLog(LG_MAIN, LG_INFO, "setting project", $projectName);
    $PM->setProject($projectName);
    appendToLog(LG_MAIN, LG_INFO, "starting docker", $PM->dockerManager->dockerMachineName);
    $PM->startDocker();
    if ($PM->stopProject($projectName)) {
        appendToLog(LG_MAIN, LG_INFO, "project stopped", $projectName);
    } else {
        appendToLog(LG_MAIN, LG_WARNING, "project NOT stopped", $projectName);
    }
}


