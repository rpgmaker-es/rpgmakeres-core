<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * This is the list of all controllers in this web. It's an key-value array following this syntax:
 * NameOfTheController => nameOfControllerFile.php (in controllers folder).
 */
$_RPGMAKERES["controllers"] = [
    "TestController" => "TestController.php",
];

/**
 * Any controller on this list will be auto-executed by CRON in the time specified in the list (in minutes).
 */
$_RPGMAKERES["manualRateControllers"] = [
    "TestManualRateController" => 20
    //TODO
];
