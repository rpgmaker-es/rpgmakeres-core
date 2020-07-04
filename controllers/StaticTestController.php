<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class DynamicTestController
 * This is just a test controller for static pages :D
 */
class StaticTestController
{
    static function _getWebPath() {
        return "/";
    }

    /**
     * Return the default method of this controller
     * @return array
     */
    static function _getDefault()
    {
        return ["index", null];
    }

    /**
     * Return all possible children of this controller. Can be dynamic.
     * @return array
     */
    static function _getChildren()
    {
        //returns an array of keyvalue-array-objects with 3 properties: URL-name, Method name and a parameter (optional, null if not)
        return [
            ["1", "childrenTest", 1],
            ["2", "childrenTest", 2],
            ["enableLoginFlag", "enableLoginFlag", null],
            ["disableLoginFlag", "disableLoginFlag", null],
        ];
    }

    /**
     * This is just a test function that serves /public/route1
     * @return false|string
     * @throws Exception
     */
    static function index()
    {
        ViewProcessor::sendHTMLHeaders();
        return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php", "home.php", []);
    }

    static function childrenTest($parameter) {
        ViewProcessor::setSkinHeadTitle("Static children view test");

        return ViewProcessor::renderHTMLWithSkin("rpgmakeres.php",
            "testView.php", [
                "title" => "Children test",
                "second_text" => "This is the children number " . $parameter
            ]);
    }

    static function enableLoginFlag() {
        return ViewProcessor::renderHTML("login/dash_login.php", []);
    }

    static function disableLoginFlag() {
        return ViewProcessor::renderHTML("login/dash_logout.php", []);
    }

}
