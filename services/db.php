<?php defined('RPGMAKERES') OR exit();

/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class DBService
 * Database Services for MySQL. Using MySQLi driver.
 */
class DBService
{

    /*
     * Database object for persistent connections
     * @var null
     */
    private static $db = NULL;

    /**
     * Connect to the MySQL database with connection parameters in config.php. If it's already connected, it returns that connection.
     * @return mysqli MySQLi connection object or NULL in case of failure.
     */
    private static function connect()
    {
        global $_RPGMAKERES;

        if (!DBService::$db) {

            DBService::$db = mysqli_connect($_RPGMAKERES["DB_HOST"], $_RPGMAKERES["DB_USER"], $_RPGMAKERES["DB_PASS"], $_RPGMAKERES["DB_NAME"]);

            if (!DBService::$db) {
                return NULL;
            }
        }

        return DBService::$db;
    }

    /**
     * Makes a test connection to assure if database connection is working.
     * @return bool true or false if connection is working or not.
     */
    public static function testDBConnection()
    {

        $db = DBService::connect();
        if (!$db) return false;
        return true;
    }

    /**
     * Obtains database object, connecting it if not connected. An alias of connect.
     * @return mysqli object.
     */
    public static function getDB()
    {
        return DBService::connect();
    }


    /**
     * Execute a unsanitized (be careful!) query and return the number of affected rows.
     * @param string $query The query being executed.
     * @return int The number of affected rows or -1 in a case of error.
     */
    public static function execQuery($query)
    {
        $db = DBService::connect();
        if (!$db) return -1;
        var_dump($query);
        $result = mysqli_query($db, $query);
        if (!$result) {
            return -1;
        }
        $arr = mysqli_affected_rows($db);
        return $arr;
    }

    /**
     * Execute a unsanitized query (be careful!) and returns results in a key-value array.
     * @param string $query The query being executed.
     * @return array|int The results on the query, or -1 in case of an error.
     */
    public static function getQuery($query)
    {
        $db = DBService::connect();
        if (!$db) return -1;
        $result = mysqli_query($db, $query);
        if (!$result) {
            return -1;
        }
        $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $arr;
    }

    /**
     * Executes a sanitized query and returns results in a key-value array.
     * @param string $query The query being executed (mark any dynamic values as ? For example: SELECT * FROM USERS WHERE ID = ? and ACTIVE = 0)
     * @param string $valuesToReplace A string containing the types of each dynamic value as a character. See https://www.php.net/manual/en/mysqli-stmt.bind-param.php
     * @param array $values An array (by reference) containing each value per dynamic value.
     * @return array|int The results on the query, or -1 in case of an error.
     */
    public static function getSecureQuery($query, $valuesToReplace, $values)
    {

        global $_RPGMAKERES;

        $db = DBService::connect();
        if (!$db) return NULL;
        $stmt = mysqli_stmt_init($db);
        if (!mysqli_stmt_prepare($stmt, $query)) {
            return -1;
        }

        if ($valuesToReplace != "") {
            $params = array_merge(array($stmt, $valuesToReplace), $values);
            call_user_func_array("mysqli_stmt_bind_param", $params);
        }

        mysqli_stmt_execute($stmt);

        //Some particular servers hasn't mysqlnd native functions, so in that case we provide replacements.
        $result = $_RPGMAKERES["useOwnMysqliStmtGetResult"] ? DBService::_fallback_get_result($stmt) : mysqli_stmt_get_result($stmt);
        if (!$result) return -1;
        $arr = array();

        if ($_RPGMAKERES["useOwnMysqliStmtGetResult"]) {
            while ($DATA = array_shift($result)) array_push($arr, $DATA);
        } else {
            $arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        mysqli_stmt_close($stmt);
        return $arr;
    }

    /**
     * Replacement function for mysqli_stmt_get_result for servers who don't implement this function.
     * @param mysqli_stmt $Statement MySQLi statement prepared query.
     * @return array Results of the query stored in an array.
     */
    public static function _fallback_get_result($Statement)
    {
        $RESULT = array();
        $Statement->store_result();
        for ($i = 0; $i < $Statement->num_rows; $i++) {
            $Metadata = $Statement->result_metadata();
            $PARAMS = array();
            while ($Field = $Metadata->fetch_field()) {
                $PARAMS[] = &$RESULT[$i][$Field->name];
            }
            call_user_func_array(array($Statement, 'bind_result'), $PARAMS);
            $Statement->fetch();
        }
        return $RESULT;
    }
}