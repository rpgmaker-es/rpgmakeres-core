<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Configuration file for RPG Maker ES Core.
 * You can provide specific parameter depending of the setup of the server.
 */
$_RPGMAKERES["config"] = array(

    /*
     * Just for testing ^^
     */
    "place" => "holder",

    /*
     * Database connection settings
     */
    "DB_HOST" => "localhost",
    "DB_NAME" => "db",
    "DB_USER" => "root",
    "DB_PASS" => "1234",
    "DB_DEBUG" => false,

    /*
     * Use own mysqli_stmt_get_result function. Use when mysql-client is not mysqlnd based.
     */
    "useOwnMysqliStmtGetResult" => false,

);
