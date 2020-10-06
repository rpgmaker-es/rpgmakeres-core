<?php defined('RPGMAKERES') or exit();

/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

class UsersModel
{
    public static function getActiveUserByUsername($username)
    {
        $out = PDOService::getSecureQuery(
            "SELECT * FROM user WHERE username = ? " . UsersModel::generateActiveConditions(),
            [PDO::PARAM_STR], [$username]);
        if ($out && count($out) > 0) return $out[0];
        return NULL;
    }

    public static function getUserByUID($uid)
    {
        $out = PDOService::getSecureQuery(
            "SELECT * FROM user WHERE uid = ? and deleted = 0",
            [PDO::PARAM_INT], [$uid]);
        if ($out && count($out) > 0) return $out[0];
        return NULL;
    }

    private static function generateActiveConditions()
    {
        return " AND suspended = 0 AND verified = 1 AND deleted = 0 ";
    }

    public static function deleteUser($uid) {
        return PDOService::execSecureQuery(
            "UPDATE user SET deleted = 1 WHERE uid = ?",
            [PDO::PARAM_INT], [$uid]);
    }
}