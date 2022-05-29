<?php defined('RPGMAKERES') or exit();

/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/


class AdminUserController
{
    static function _getWebPath()
    {
        return "admin/user/";
    }

    /**
     * Return the default method of this controller
     * @return array
     */
    static function _getDefault()
    {
        return ["userList", null];
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
            ["delete", "userDelete", null],
            ["view", "userView", null],
            ["create", "userEdit", null],
            ["edit", "userEdit", null]
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

    static function userlist() {
        RPGMakerES::loadService("session");
        SessionService::checkAdmin();
        $csrf = SessionService::regenerateCSRF();

        RPGMakerES::loadService("db_pdo");
        RPGMakerES::loadService("validation");
        RPGMakerES::loadService("pagination");

        RPGMakerES::loadModel("UsersModel");

        //Obtain pagination parameters
        $paginationParameters = PaginationService::getPaginationGetParams();


        $paginationParameters =PaginationService::mapSearchValues($paginationParameters, "active", ["Activo" => 1, "Inactivo" => 0]);
        $paginationParameters =PaginationService::mapSearchValues($paginationParameters, "suspended", ["suspendido" => 1]);
        $paginationParameters =PaginationService::mapSearchValues($paginationParameters, "verified", ["ok" => 1, "no" => 0]);
        $paginationParameters =PaginationService::mapSearchValues($paginationParameters, "permissions", ["Usuario" => 0, "Administrador" => 4]);



        $out = UsersModel::getUsers($paginationParameters["page"], $paginationParameters["search"], $paginationParameters["order"]);
        $users = $out["result"];
        $paginationParameters["count"] = $out["count"];

        $content = ViewProcessor::renderHTML("admin/userlist.php", [
            "users" => $users,
            "paginationParameters"=> $paginationParameters,
            "csrf" => $csrf
        ]);

        return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
            "admin/base.php", [
                "content" => $content
            ]);
    }

    static function userEdit() {
        RPGMakerES::loadService("session");
        SessionService::checkAdmin();
        RPGMakerES::loadService("validation");

        if (array_key_exists("submit", $_POST)
            && array_key_exists("csrf", $_POST) && SessionService::validateCRSF($_POST["csrf"]) ) {
            //submit!
            RPGMakerES::loadService("db_pdo");
            RPGMakerES::loadModel("UsersModel");
            $user = array_key_exists("uid", $_GET)?UsersModel::getUserByUID($_GET["uid"]):false;
            if ($user && $user["permissions"] > $_SESSION['user']["permissions"] && $_SESSION['user']["permissions"] != "4") {
                return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                    "admin/information_message.php", [
                        "title" => "No se pudo editar el usuario",
                        "message" => "El usuario tiene un nivel de privilegio superior al suyo.",
                        "return_url" => "/admin/user"
                    ]);
            }

            //validation
            if (!ValidationService::isFilled($_POST["username"]) || !ValidationService::isValidString($_POST["username"], 15)) ValidationService::addError("username", "El nombre de usuario no es válido");
            if (!ValidationService::isFilled($_POST["email"]) || !ValidationService::isValidEmail($_POST["email"], 50)) ValidationService::addError("email", "El correo electronico no es válido");
            $password_validation = (ValidationService::isValidPassword($_POST["password"], 50));
            if (!$user) {
                //mandatory only if new usuer
                if (!$password_validation) ValidationService::addError("password", "La password no es valida");
            }

            if (!array_key_exists("active", $_POST)) $_POST["active"]="0";
            if (!array_key_exists("suspended", $_POST)) $_POST["suspended"]="0";
            if (!array_key_exists("verified", $_POST)) $_POST["verified"]="0";

            if (!ValidationService::isFilled($_POST["active"]) || !ValidationService::isValidNumber($_POST["active"], 0,1)) ValidationService::addError("active", "No se pudo determinar si el usuario se marcó activo");
            if (!ValidationService::isFilled($_POST["suspended"]) || !ValidationService::isValidNumber($_POST["suspended"], 0,1)) ValidationService::addError("suspended", "No se pudo determinar si el usuario se marcó suspendido");
            if (!ValidationService::isFilled($_POST["verified"]) || !ValidationService::isValidNumber($_POST["verified"], 0,1)) ValidationService::addError("verified", "No se pudo determinar si el usuario se marcó verificado");

            if (!ValidationService::isFilled($_POST["permissions"]) || !ValidationService::isValidNumber($_POST["permissions"], 0,4)) ValidationService::addError("permissions", "No se pudo determinar los permisos del usuario");

            //TODO
            //if (!ValidationService::isValidString($_POST["url1"], 50)) ValidationService::addError("url1", "la URL 1 no es válida");
            //if (!ValidationService::isValidString($_POST["url2"], 50)) ValidationService::addError("url2", "la URL 2 no es válida");
            //if (!ValidationService::isValidString($_POST["url3"], 50)) ValidationService::addError("url3", "la URL 3 no es válida");
            //if (!ValidationService::isValidString($_POST["url4"], 50)) ValidationService::addError("url4", "la URL 4 no es válida");

            if (!ValidationService::hasErrors()) {
                RPGMakerES::loadService("db_pdo");
                RPGMakerES::loadModel("UsersModel");
                if ($user) {
                    //update
                    die("update!");

                } else {
                    //create
                    if (UsersModel::addUser($_POST)) {
                        return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                            "admin/information_message.php", [
                                "title" => "Se ha agregado a " . $_POST["username"],
                                "message" => "El usuario ha sido agregado existosamente.",
                                "return_url" => "/admin/user"
                            ]);
                    } else {
                        return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                            "admin/information_message.php", [
                                "title" => "Ha ocurrido un error de base de datos al agregar a " . $_POST["username"],
                                "message" => "Un error ocurrio al momento de agregar al usuario",
                                "return_url" => "/admin/user"
                            ]);
                    }
                }
            }
        }

        $csrf = SessionService::regenerateCSRF();
        RPGMakerES::loadService("forms");
        if (array_key_exists("uid", $_GET) && ValidationService::isValidNumber($_GET["uid"])) {
            //edit
            RPGMakerES::loadService("db_pdo");
            RPGMakerES::loadModel("UsersModel");
            $user = UsersModel::getUserByUID($_GET["uid"]);
            if (!$user) {
                return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                    "admin/information_message.php", [
                        "title" => "No se puede editar el usuario",
                        "message" => "El usuario no existe.",
                        "return_url" => "/admin/user"
                    ]);
            }
            if ($user["permissions"] > $_SESSION['user']["permissions"] && $_SESSION['user']["permissions"] != "4") {
                return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
                    "admin/information_message.php", [
                        "title" => "No se pudo editar el usuario",
                        "message" => "El usuario tiene un nivel de privilegio superior al suyo.",
                        "return_url" => "/admin/user"
                    ]);
            }
        } else {
            $user = [];
        }
        FormsService::replaceUserWithPreFilledData($user);

        $content = ViewProcessor::renderHTML("admin/userEdit.php", [
            "user" => $user,
            "csrf" => $csrf
        ]);

        return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
            "admin/base.php", [
                "content" => $content
            ]);
    }

    static function userDelete() {
        RPGMakerES::loadService("session");
        SessionService::checkAdmin();
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