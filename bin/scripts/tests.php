<?php

///////////////////////////////////////////////////////////////////////////////
require_once __DIR__ . "/../api/libs/vendor/autoload.php";
require_once __DIR__ . "/../api/libs/dockerManager.php";
require_once __DIR__ . "/../api/libs/reposManager.php";
require_once __DIR__ . "/../api/libs/projectsManager.php";
require_once __DIR__ . "/../api/libs/tools.php";

///////////////////////////////////////////////////////////////////////////////
$CONFIG_FOLDER = __DIR__ . "/../config";
$CONFIG_FILE = __DIR__ . "/../config/config.json";
$PROJECTS_CONFIG_FILE = __DIR__ . "/../config/projects.json";

///////////////////////////////////////////////////////////////////////////////
$config = jsonFileToObject($CONFIG_FILE);

///////////////////////////////////////////////////////////////////////////////
$dockerManager = new dockerManager($config->dockerMachineName);
$reposManager = new reposManager($config->repositoryBaseURL, makePath($CONFIG_FOLDER, "id_rsa"), $config->workBaseFolder, $config->dockerFolder);
$projectsManager = new projectsManager($reposManager, $dockerManager, $PROJECTS_CONFIG_FILE, $config->defaultProjectEnvironmentVariable, $config->publicAutoPortOffset);

///////////////////////////////////////////////////////////////////////////////
$runningProjects = $projectsManager->getRunningProjects();
appendToLog(LG_MAIN, LG_INFO, "running projects");
print_r($runningProjects);

///////////////////////////////////////////////////////////////////////////////
$testProjectName = "tsdcwuissl";
$testEnvironment = "local";
if ($projectsManager->isProjectRunning($testProjectName, $testEnvironment)) {
    appendToLog(LG_MAIN, LG_INFO, "project", $testProjectName, "is running in env ", $testEnvironment);
} else {
    appendToLog(LG_MAIN, LG_INFO, "project", $testProjectName, "is NOT running in env ", $testEnvironment);
}

///////////////////////////////////////////////////////////////////////////////
appendToLog(LG_MAIN, LG_INFO, "cleaning old containers");
$removeds = $projectsManager->dockerManager->removeOldContainers();
print_r($removeds);

if ($projectsManager->addProject("otherOtherTest", "testRepo", array(34, 24), "testEnv")) {
    appendToLog(LG_MAIN, LG_INFO, "project added");
} else {
    appendToLog(LG_MAIN, LG_INFO, "project not added");
}

///////////////////////////////////////////////////////////////////////////////
$testProjectName = "tsdcwuissl";
$testEnvironment = "local";
appendToLog(LG_MAIN, LG_INFO, "stoping project", $testProjectName, $testEnvironment);
if ($projectsManager->stopProject($testProjectName, $testEnvironment)) {
    appendToLog(LG_MAIN, LG_INFO, "project stopped");
} else {
    appendToLog(LG_MAIN, LG_INFO, "project NOT stopped");
}