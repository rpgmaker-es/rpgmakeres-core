<?php defined('RPGMAKERES') or exit();

/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

class LoginController
{
    static function _getWebPath()
    {
        return "login/";
    }

    /**
     * Return the default method of this controller
     * @return array
     */
    static function _getDefault()
    {
        return ["login", null];
    }

    /**
     * Return all possible children of this controller. Can be dynamic.
     * @return array
     */
    static function _getChildren()
    {
        //returns an array of keyvalue-array-objects with 3 properties: URL-name, Method name and a parameter (optional, null if not)
        return [
            ["logout", "logout", null],
            ["debugsessioncheck", "debugsessioncheck", null],
            ["logindashboard", "logindashboard", null]
            //["ajustes", "ajustes", null]
        ];
    }

    static function login()
    {
        RPGMakerES::loadService("session");
        SessionService::useSession();

        //check if we already logged in
        if (SessionService::isSessionInitialized()) {
            header('Location: /');
            die();
        }

        $errorLogin = false;

        if (
            array_key_exists("submit", $_POST) &&
            array_key_exists("user", $_POST) &&
            array_key_exists("pass", $_POST)
        ) {
            //honeypot checking
            if (array_key_exists("email", $_POST) && $_POST["email"]!= "") die();

            RPGMakerES::loadService("validation");

            $remember_me = false;
            if (array_key_exists("cc", $_POST)) $remember_me = true;

            $validationPass = true;
            if (!ValidationService::isValidString($_POST["user"], 12)) $validationPass = false;
            if (!ValidationService::isValidString($_POST["pass"], 255)) $validationPass = false;

            if ($validationPass) {
                //match login
                RPGMakerES::loadModel("UsersModel");
                $user = UsersModel::getActiveUserByUsername($_POST["user"]);
                if ($user) {
                    //user found, match password
                    RPGMakerES::loadService("password");
                    if (PasswordService::checkPassword($_POST["pass"], $user["password"])) {

                        //clean password from user object and register it as session
                        $user["password"] = "";
                        SessionService::useSession($user);

                        //redirect to home
                        header('Location: /');
                        die();
                    }
                }
                $errorLogin = true;
            }
        }

        ViewProcessor::sendHTMLHeaders();
        ViewProcessor::setSkinHeadTitle("Inicio de sesión");

        return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
            "login.php", [
                "errorLogin" => $errorLogin,
            ]);
    }

    public static function logindashboard() {
        RPGMakerES::loadService("session");
        SessionService::useSession();

        //check if we already logged in
        if (SessionService::isSessionInitialized()) return ViewProcessor::renderHTML("login/dash_login.php", ["user" => $_SESSION["user"]]);

        //otherwise render this instead
        return ViewProcessor::renderHTML("login/dash_logout.php", []);
    }

    public static function debugsessioncheck() {
        RPGMakerES::loadService("session");
        SessionService::useSession();
        SessionService::mustLogin();
        var_dump($_SESSION['user']);
        die();
    }

    public static function logout() {
        RPGMakerES::loadService("session");
        SessionService::useSession();
        SessionService::destroySession();
        header('Location: /');
        die();
    }
}