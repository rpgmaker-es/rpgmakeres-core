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

if (!@include_once  RPGMakerES::GetRootFolder("config.php") ) die("Config.php not found");

//setting PHP configurations
global $_RPGMAKERES;

ini_set( 'session.cookie_httponly', 1 );
ini_set('session.gc_maxlifetime', $_RPGMAKERES["config"]["sessionTimeout"]);
