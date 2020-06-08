<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class RPGMakerES
 * Base basic functions of RPG Maker ES Core.
 */
Class RPGMakerES
{

    /**
     * Get a path from a PHP file of RPG Maker ES Core, for using in includes and other things.
     * @param string $path String Optional, a path to append to the output of this function.
     * @return string The path of RPG Maker ES core.
     */
    static function get_rootfolder($path = "")
    {
        return $_SERVER['DOCUMENT_ROOT'] . "/../" . $path;
    }


    /**
     * Calls a controller and returns the output of it.
     * @param String $controller  Controller String ID, as defined in routes.php
     * @param String $function  The function name in the controller.
     */
    static function generate_dyn($controller, $function)
    {
        include_once "ControllerSolver.php";
        include_once "ViewProcessor.php";
        return ControllerSolver::call($controller, $function);

    }

    /**
     * Generates a 404 error.
     * @param string $arguments Additional information.
     * @return string The generated 404 error
     */
    static function gen404($arguments = "")
    {
        http_response_code(404);
        return "404 Not found.<br>" . $arguments;
    }

    /**
     * Generates a 403 error.
     * @param string $arguments Additional information.
     * @return string The generated 403 error
     */
    static function gen403($arguments = "")
    {
        http_response_code(403);
        return "403 Forbidden<br>" . $arguments;
    }

    /**
     * Generates a 501 error.
     * @param string $arguments Additional information.
     * @return string The generated 501 error
     */
    static function gen501($arguments = "")
    {
        http_response_code(501);
        return "501 Not implemented<br>" . $arguments;
    }

    /**
     * Generates a 500 error, for PHP fatal errors.
     * @param Exception $arguments An exception that caused this 500 error.
     * @return string The generated 500 error
     */
    static function gen500($trace)
    {
        http_response_code(500);
        return "500 Internal server error<br>" . json_encode($trace);
    }

}