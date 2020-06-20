<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class ViewProcessor
 * Functions related to View generation and web renderization.
 */
class ViewProcessor
{
    /**
     * @var string Title screen that the next page to be rendererd will be used
     */
    static $title = "";
    /**
     * Renders a view from a file with a list of variables to be applied on it, and returns a string with it.
     * @param string $viewFile File that containts the view (located in views folder)
     * @param array $contextVariables A key-value array that contains all variables that will be passed to the view.
     * @return false|string Returns a string with the rendered view or false if nothing has to be rendered.
     * @throws Exception An excetption will be thrown if view file can't be opened.
     */
    static function renderHTML($viewFile, $contextVariables)
    {
        //validating render file
        $filePath = RPGMakerES::GetRootFolder("/views/" . $viewFile);

        if (!file_exists($filePath)) {
            throw new Exception("The specified view filename does not exist: " . $viewFile);
        }

        //preparing context variables
        extract($contextVariables);


        //prepare the envoriment for storing next output to a stream.
        ob_start();

        //process view
        if (!@include($filePath)) {
            throw new Exception("Unable to open view file: " . $viewFile);
        }

        //ends buffer output
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    static function setSkinHeadTitle($title) {
        ViewProcessor::$title = $title;
    }

    /**
     * Renders a view with a skin from a file with a list of variables to be applied on it, and returns a string with it.
     * @param string $skinFile File that containts the skin (located in skins folder)
     * @param string $viewFile File that containts the view (located in views folder)
     * @param array $contextVariables A key-value array that contains all variables that will be passed to the view.
     * @return false|string Returns a string with the rendered view or false if nothing has to be rendered.
     * @throws Exception An excetption will be thrown if view file can't be opened.
     */
    static function renderHTMLWithSkin($skinFile, $viewFile, $contextVariables) {

        //attempt to load the skin
        $skinPath = RPGMakerES::GetRootFolder("/skins/" . $skinFile);
        if (!file_exists($skinPath)) {
            throw new Exception("The specified skin filename does not exist: " . $skinFile);
        }

        //set up parameters to be given to the skin, specifically the head title
        $skinVariables = ["title" => ViewProcessor::$title];
        ViewProcessor::setSkinHeadTitle("");

        //render inner html first
        $skinVariables["_output"] = ViewProcessor::renderHTML($viewFile, $contextVariables);

        //preparing skin context variables
        extract($skinVariables);

        //prepare the envoriment for storing next output to a stream.
        ob_start();

        //process view
        if (!@include($skinPath)) {
            throw new Exception("Unable to open skin file: " . $skinPath);
        }

        //ends buffer output
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Sends default headers to render HTML files to the browser.
     */
    static function sendHTMLHeaders()
    {
        if (RPGMakerES::isBrowser()) {
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
            header('Content-type: text/html;charset=UTF-8');
        }
    }
}