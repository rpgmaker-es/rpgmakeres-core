<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class ControllerSolver
 * Controller locating and calling based functions.
 */
class ControllerSolver
{

    /**
     * Calls a function from a provided Controller ID
     * @param $controllerName String Controller Name ID, as specificied in routes.php
     * @param $controllerFunction String Function Name in Controller file.
     * @return mixed|string The output of the function or the corresponding errors.
     */
    static function call($controllerName, $controllerFunction)
    {
        global $_RPGMAKERES;
        include_once(RPGMakerES::getRootFolder("routes.php"));

        //The controller exists?
        if (!array_key_exists($controllerName, $_RPGMAKERES["controllers"])) {
            return RPGMakerES::gen501("Controller not found in controller list");
        }

        //Load controller
        if (!@include_once(RPGMakerES::getRootFolder("controllers/") . $_RPGMAKERES["controllers"][$controllerName])) {
            return RPGMakerES::gen501("Controller not found");
        }

        //(Try to) call the function inside the controller.
        try {
            return call_user_func($controllerName . "::" . $controllerFunction);
        } catch(Exception $e) {
            return RPGMakerES::gen500($e);
        }
    }

}
