<?php defined('RPGMAKERES') or exit();

/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/


class AdminController
{
    static function _getWebPath()
    {
        return "admin/";
    }

    /**
     * Return the default method of this controller
     * @return array
     */
    static function _getDefault()
    {
        return ["admin", null];
    }

    /**
     * Return all possible children of this controller. Can be dynamic.
     * @return array
     */
    static function _getChildren()
    {
        //returns an array of keyvalue-array-objects with 3 properties: URL-name, Method name and a parameter (optional, null if not)
        return [
            //["ajustes", "ajustes", null]
            ["user", "userList", null],
            ["userdelete", "userDelete", null],
            ["usercreate", "userEdit", null]
        ];
    }

    static function admin()
    {
        RPGMakerES::loadService("session");
        SessionService::checkAdmin();

        $content = ViewProcessor::renderHTML("admin/home.php", []);

        return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
            "admin/base.php", [
                "content" => $content,
            ]);
    }
}