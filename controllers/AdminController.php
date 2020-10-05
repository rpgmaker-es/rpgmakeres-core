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
        ];
    }

    private static function checkAdmin() {
        RPGMakerES::loadService("session");
        SessionService::useSession();
        SessionService::mustLogin();
        if (!array_key_exists("permissions", $_SESSION['user']) || $_SESSION['user']['permissions'] != 4 ) {
            global $_RPGMAKERES;
            header('Location: ' . $_RPGMAKERES["config"]["loginUrl"]);
            die();
        }
    }

    static function admin()
    {
        AdminController::checkAdmin();

        $content = ViewProcessor::renderHTML("admin/home.php", []);

        return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
            "admin/base.php", [
                "content" => $content,
            ]);
    }
}