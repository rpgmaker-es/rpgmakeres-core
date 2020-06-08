<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class TestController
 * This is just a test controller :D
 */
class TestController
{

    /**
     * This is just a test function that serves /public/route1
     * @return false|string
     * @throws Exception
     */
    static function testFunction()
    {
        ViewProcessor::sendHTMLHeaders();

        return ViewProcessor::renderHTML("testView.php", [
            "title" => "Hello world!",
            "second_text" => "I'm rendering a view!"
        ]);
    }

}
