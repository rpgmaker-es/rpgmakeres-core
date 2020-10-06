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
            ["userdelete", "userDelete", null]
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

    static function userlist() {
        AdminController::checkAdmin();
        $csrf = SessionService::regenerateCSRF();

        RPGMakerES::loadService("db_pdo");

        $users = PDOService::getQuery("SELECT * FROM user WHERE deleted = 0");
        $content = ViewProcessor::renderHTML("admin/userlist.php", ["users" => $users, "csrf" => $csrf]);

        return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
            "admin/base.php", [
                "content" => $content
            ]);
    }

    static function userDelete() {
        AdminController::checkAdmin();
        RPGMakerES::loadService("validation");

        if (array_key_exists("uid", $_GET) && ValidationService::isValidNumber($_GET["uid"])
            && array_key_exists("csrf", $_GET) && SessionService::validateCRSF($_GET["csrf"]) )  {
            //verificar que el usuario no sea id 0
            if ($_GET["uid"] == "1") {
                return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                    "admin/information_message.php", [
                        "title" => "No se pudo eliminar el usuario",
                        "message" => "El usuario es UID 0, lo que potencialmente es un superusuario.",
                        "return_url" => "/admin/user"
                    ]);
            }

            RPGMakerES::loadService("db_pdo");
            RPGMakerES::loadModel("UsersModel");
            $user = UsersModel::getUserByUID($_GET["uid"]);
            if (!$user) {
                return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                    "admin/information_message.php", [
                        "title" => "No se pudo eliminar el usuario",
                        "message" => "El usuario no existe.",
                        "return_url" => "/admin/user"
                    ]);
            }

            if ($user["permissions"] >= $_SESSION['user']["permissions"] && $_SESSION['user']["permissions"] != "4") {
                return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                    "admin/information_message.php", [
                        "title" => "No se pudo eliminar el usuario",
                        "message" => "El usuario tiene un nivel de privilegio igual o superior al suyo.",
                        "return_url" => "/admin/user"
                    ]);
            }

            if ($user["uid"] == $_SESSION['user']["uid"]) {
                return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                    "admin/information_message.php", [
                        "title" => "No se pudo eliminar el usuario",
                        "message" => "No puede eliminarse a usted mismo.",
                        "return_url" => "/admin/user"
                    ]);
            }

            //eliminar
            $out = UsersModel::deleteUser($user["uid"]);
            if ($out < 1) {
                return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                    "admin/information_message.php", [
                        "title" => "No se pudo eliminar el usuario",
                        "message" => "La base de datos indica que no hubieron registros modificados. ¿El usuario realmente existe?",
                        "return_url" => "/admin/user"
                    ]);
            } else {
                return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                    "admin/information_message.php", [
                        "title" => "Se ha eliminado a " . $user["username"],
                        "message" => "El usuario ha sido eliminado existosamente.",
                        "return_url" => "/admin/user"
                    ]);
            }


        } else {
            header('Location: /admin/user');
        }
    }

}