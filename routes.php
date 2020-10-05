<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

global $_RPGMAKERES;

/**
 * This is the list of all controllers in this web. It's an key-value array following this syntax:
 * NameOfTheController => nameOfControllerFile.php (in controllers folder).
 */
$_RPGMAKERES["controllers"] = [
    "StaticTestController" => "StaticTestController.php",
    "DynamicTestController" => "DynamicTestController.php",
    "LoginController" => "LoginController.php",
    "AdminController" => "AdminController.php",
];

/**
 * List of static pages on this web and it's time for refresh.
 * If it's 0 or false, it's configured as Static-OnDemand and it only will be updated on demand.
 * If it's any value > 0, then it will be updated by the amount of minutes specified here by cron.
 */
$_RPGMAKERES["staticPages"] = [
    "StaticTestController" => 5
];

/**
 * List of dynamic pages on this web.
 * Used for sanity check on cron
 */
$_RPGMAKERES["dynamicPages"] = [
    "DynamicTestController",
    "LoginController",
    "AdminController"
];

/**
 * List of controlers in black list.
 * All pages by these controllers in this list will be deleted in next updates, so it can deleted safely later.
 * Be sure to remove first from staticPages and DynamicPages first, or the core will be generate-and-delete them in
 * each-update.
 */
$_RPGMAKERES["blackListPages"] = [

];