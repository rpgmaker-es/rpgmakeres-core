<?php defined('RPGMAKERES') OR exit();

/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

class PDOService
{
    /*
     * Database object for persistent connections
     * @var null
     */
    private static $db = NULL;

    /**
     * Connect to the PDO database with connection parameters in config.php. If it's already connected, it returns that connection.
     * @return PDO|null
     */
    public static function connect()
    {
        global $_RPGMAKERES;

        if (!PDOService::$db ) {
            PDOService::$db = new \PDO(
                $_RPGMAKERES["config"]["PDO_dsn"],
                $_RPGMAKERES["config"]["PDO_user"],
                $_RPGMAKERES["config"]["PDO_pass"],
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            if (!PDOService::$db ) {
                return NULL;
            }
        }

        return PDOService::$db;
    }

    /**
     * Makes a test connection to assure if database connection is working.
     * @return bool true or false if connection is working or not.
     */
    public static function testDBConnection()
    {
        $db = PDOService::connect();
        if (!$db) return false;
        return true;
    }

    /**
     * Obtains database object, connecting it if not connected. An alias of connect.
     * @return PDO|null
     */
    public static function getDB()
    {
        return PDOService::connect();
    }

    /**
     * Execute a unsanitized (be careful!) query and return the number of affected rows.
     * @param string $query The query being executed.
     * @return int The number of affected rows or -1 in a case of error.
     */
    public static function execQuery($query) {

        $db = PDOService::connect();
        if (!$db) return -1;
        $result = $db->query($query);
        if (!$result) {
            return -1;
        }

        $out = $result->rowCount();
        $result->closeCursor();

        return $out;
    }

    /**
     * Executes a sanitized query and returns the number of affected rows.
     * @param string $query The query being executed (mark any dynamic values as ? For example: SELECT * FROM USERS WHERE ID = ? and ACTIVE = 0)
     * @param array $types An array defining data types of each dynamic value of the query. See https://www.php.net/manual/es/pdo.constants.php for possible data types
     * @param array $values An array (by reference) containing each value per dynamic value.
     * @return int The number of affected rows or -1 in a case of error.
     */
    public static function execSecureQuery($query, $types, $values)
    {
        $db = PDOService::connect();
        if (!$db) return -1;

        $stmt = PDOService::_prepare_query_statement($db, $query, $types, $values);
        $stmt->execute();
        $out = $stmt->rowCount();
        $stmt->closeCursor();
        return $out;
    }

    /**
     * Execute a unsanitized query (be careful!) and returns results in a key-value array.
     * @param string $query The query being executed.
     * @return array|int The results on the query, or -1 in case of an error.
     */
    public static function getQuery($query) {
        $db = PDOService::connect();
        if (!$db) return -1;
        $result = $db->query($query);
        if (!$result) {
            return -1;
        }

        $out = $result->fetchAll(PDO::FETCH_ASSOC);
        $result->closeCursor();

        return $out;
    }

    /**
     * Executes a sanitized query and returns results in a key-value array.
     * @param string $query The query being executed (mark any dynamic values as ? For example: SELECT * FROM USERS WHERE ID = ? and ACTIVE = 0)
     * @param array $types An array defining data types of each dynamic value of the query. See https://www.php.net/manual/es/pdo.constants.php for possible data types
     * @param array $values An array (by reference) containing each value per dynamic value.
     * @return array|int The results on the query, or -1 in case of an error.
     */
    public static function getSecureQuery($query, $types, $values)
    {
        $db = PDOService::connect();
        if (!$db) return -1;

        $stmt = PDOService::_prepare_query_statement($db, $query, $types, $values);
        $stmt->execute();
        $out = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $out;
    }

    /**
     * Disconnects from a database if it's connected.
     */
    public static function disconnect() {
        if (PDOService::$db ) {
            PDOService::$db = NULL; //it will call destructor
        }
    }

    /**
     * Prepares a database statement query with the designed parameters.
     * @param PDO $db PDO database object
     * @param string $query The query being executed (mark any dynamic values as ? For example: SELECT * FROM USERS WHERE ID = ? and ACTIVE = 0)
     * @param array $types An array defining data types of each dynamic value of the query. See https://www.php.net/manual/es/pdo.constants.php for possible data types
     * @param array $values An array (by reference) containing each value per dynamic value.
     * @return PDOStatement|int A statement ready for executing, or -1 in case of an error.
     */
    public static function _prepare_query_statement($db, $query, $types, $values) {

        if (count($types)!= count($values)) return -1;

        $stmt = $db->prepare($query);
        if (!$stmt) return -1;

        for ($i=0; $i<count($types); $i++) {
            //see https://www.php.net/manual/es/pdo.constants.php for possible data types
            $stmt->bindParam($i+1, $values[$i], $types[$i]);
        }

        return $stmt;
    }
}
