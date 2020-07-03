<?php defined('RPGMAKERES') OR exit();

/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class SessionService
 * Session based functionalities
 */
class SessionService
{
    /**
     * @var bool Specifies if a session is currently initalized
     */
    private static $session_initalized = false;

    /**
     * Setup (or restart) a session in PHP, only if it's valid. If userdata is provided it will be written in session.
     * @param array|bool $userData Optional, if defined it will be written at the session.
     */
    public static function useSession($userData = false)
    {
        session_start();
        SessionService::$session_initalized = true;

        if ($userData) {
            $_SESSION['user'] = $userData;
        } else {
            SessionService::_validateSession();
        }

        $_SESSION['last_activity'] = time();
    }

    /**
     * Destroy the current session and it's data.
     */
    public static function destroySession()
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
        SessionService::$session_initalized = false;
    }

    /**
     * Checks if there's a valid session. If not, it redirects to a login screen.
     */
    public static function mustLogin()
    {
        if (!SessionService::$session_initalized) {
            global $_RPGMAKERES;
            header('Location: ' . $_RPGMAKERES["config"]["loginUrl"]);
            die();
        }
    }

    public static function isSessionInitialized() {
        return SessionService::$session_initalized;
    }

    /**
     * Verifies if the current session is valid or not
     * @return bool True or false depending if the session is valid or not.
     */
    public static function _validateSession()
    {

        global $_RPGMAKERES;

        if (!SessionService::$session_initalized) return false;

        if (!array_key_exists("user", $_SESSION)) {
            //session with no user data
            SessionService::DestroySession();
            return false;
        }

        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_RPGMAKERES["config"]["sessionTimeout"])) {
            //expired session
            SessionService::DestroySession();
            return false;
        }

        return true;
    }

}