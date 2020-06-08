<?php
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Main initialization script of RPG Maker ES Core.
 */

define("RPGMAKERES", 1);
$GLOBALS["_RPGMAKERES"] = [];

include_once "CoreFunctions.php";


if (!@include_once  RPGMakerES::get_rootfolder("config.php") ) die("Config.php not found");

