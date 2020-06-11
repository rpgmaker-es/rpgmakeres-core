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
     * Database connection settings for MySQLi
     */
    "MySQLi_DB_HOST" => "localhost",
    "MySQLi_DB_NAME" => "db",
    "MySQLi_DB_USER" => "root",
    "MySQLi_DB_PASS" => "1234",
    "MySQLi_DB_DEBUG" => false,

    /*
 * Use own mysqli_stmt_get_result function. Use when mysql-client is not mysqlnd based.
 */
    "MySQLi_useOwnMysqliStmtGetResult" => false,

    /*
     * Database connection settings for PDO. If your database does not use some of these parameters you can set these as NULL.
     */
    "PDO_dsn" => "mysql:host=localhost;dbname=db",
    "PDO_user" => "root",
    "PDO_pass" => "1234",

    /**
     * Timeout for user inactivity, in seconds
     */
    "sessionTimeout" => 1800,

    /**
     * URL for login, used for failed logged in checks
     */
    "loginUrl" => "/login",

    /*
     * Show error traces in 500 based errors.
     * Regardeless of this option, error will be written at Apache's log or stderr.
     */
    "showTracesIn500Error" => true


);
