<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

include_once "ControllerSolver.php";

/**
 * Class WebGenerator
 * Functions for static and dynamic pages generator. Use it with cron.php
 */
class WebGenerator
{
    /**
     * Poor's man enum to differentiate dynamic and static pages.
     * @var int
     */
    static $DYNAMIC = 0;
    static $STATIC = 1;

    /**
     * An alias to refer that the user wants to update the default child on a controller
     * @var string
     */
    static $DEFAULT_CHILD_ALIAS = "//__default__//";

    /**
     * Generate and/or update all static and dynamic pages, depending of their status.
     * @param bool $force If true, all pages will be regenerated.
     * @throws Exception If there's issues with file permissions.
     */
    static function generate($force = false)
    {
        global $_RPGMAKERES;
        include_once(RPGMakerES::getRootFolder("routes.php"));

        //check if public folder exists
        if (!@is_dir(RPGMakerES::getRootfolder("public"))) {
            //attempt to create it
            if (!RPGMakerES::isBrowser()) echo RPGMakerES::getRootfolder("public") . PHP_EOL;
            $out = mkdir(RPGMakerES::getRootfolder("public"));
            if (!$out) throw new Exception("Unable to create public folder. Check file permissions!");
        }

        //list all dynamic pages
        foreach ($_RPGMAKERES["dynamicPages"] as $page) {
            WebGenerator::_processPages(WebGenerator::$DYNAMIC, $page, $force);
        }

        //now static pages
        foreach ($_RPGMAKERES["staticPages"] as $page => $minutes) {
            WebGenerator::_processPages(WebGenerator::$STATIC, $page, $force, $minutes);
        }

        //check for controllers in the blacklist and then delete one each one.
        foreach ($_RPGMAKERES["blackListPages"] as $page) {
            WebGenerator::deleteControllerPages($page);
        }
    }

    /**
     * Search and generate a single controller, if exists.
     * @param String $controller The controller name, as defined in routes.php
     * @param bool $force If true, it will be generated regardless if it already exists or not
     * @param string $child If defined, it will only update the specified child in controller.
     * @return bool True if the controller is updated. False if controller does not exist.
     * @throws Exception
     */
    static function generateSingle($controller, $force = false, $child = NULL)
    {
        global $_RPGMAKERES;
        include_once(RPGMakerES::getRootFolder("routes.php"));

        //seek if the desired controller is in one of the routes path. If so, then generate it.
        foreach ($_RPGMAKERES["dynamicPages"] as $page) {
            if ($page == $controller) {
                WebGenerator::_processPages(WebGenerator::$DYNAMIC, $page, $force, 0, $child);
                return true;
            }
        }
        foreach ($_RPGMAKERES["staticPages"] as $page => $minutes) {
            if ($page == $controller) {
                WebGenerator::_processPages(WebGenerator::$STATIC, $page, $force, $minutes, $child);
                return true;
            }
        }

        //not found
        return false;
    }

    /**
     * Deletes a folder and its contents from public. It validates if the path is valid.
     * @param String $route The route that needs to be deleted, from public. Absolute paths are not accepted. 
     * @return bool True or false depending if the deletion was a success.
     */
    static function deleteRoute($route)
    {
        //filter that route by just leaving A-Z a-z 0-9 and /, it can be dirty
        $route = preg_replace("/[^a-zA-Z0-9\/]/", "", $route);

        if (@is_dir(RPGMakerES::getRootfolder("public/" . $route))) {
            if (!RPGMakerES::isBrowser()) echo "Deleting " . RPGMakerES::getRootfolder("public/" . $route) . PHP_EOL;
            return WebGenerator::_delTree(RPGMakerES::getRootfolder("public/" . $route));
        } else {
            if (!RPGMakerES::isBrowser()) echo "WARNING: Route not found in public: " . $route . PHP_EOL;
            return false;
        }
    }

    /**
     * Deletes a folder and its contents from a Controller name.
     * @param String $controllerName ControllerName as defined in routes.php
     * @return bool True or false, depending if the process returned successfully or not.
     * @throws Exception
     */
    static function deleteControllerPages($controllerName)
    {
        //Get associated route from controller, and delete that one
        $path = ControllerSolver::call($controllerName, "_getWebPath");
        return WebGenerator::deleteRoute($path);
    }

    /**
     * It process pages from a specific Controller.
     * @param int $mode WebGenerator::$DYNAMIC or WebGenerator::$STATIC.
     * @param String $page Controller name, as configured in routes.php
     * @param bool $force If true, all pages of the controller will be regenerated
     * @param int $timeout Optional, Static only. If > 0, pages of the controller will be regenerated after x minutes.
     * @param string $childForcing If defined, it will only update the specified child in controller.
     * @throws Exception If there's issues with file permissions.
     */
    static function _processPages($mode, $page, $force, $timeout = null, $childForcing = NULL)
    {
        global $_RPGMAKERES;

        //attempt to load the involved controller
        if (array_key_exists($page, $_RPGMAKERES["controllers"])) {
            //attempt to obtain essential data
            $path = ControllerSolver::call($page, "_getWebPath");
            $default_method = ControllerSolver::call($page, "_getDefault");
            $children = ControllerSolver::call($page, "_getChildren");

            //check path folders in a recursive way
            $path = explode("/", $path);
            for ($i = 0; $i < count($path); $i++) {
                //rebuild path
                $currentPath = implode("/", array_slice($path, 0, $i + 1));
                if (!@is_dir(RPGMakerES::getRootfolder("public/" . $currentPath))) {
                    //attempt to create it
                    if (!RPGMakerES::isBrowser()) echo RPGMakerES::getRootfolder("public/" . $currentPath) . PHP_EOL;
                    $out = mkdir(RPGMakerES::getRootfolder("public/" . $currentPath));
                    if (!$out) throw new Exception("Unable to create folder public/" . $currentPath . ". Check file permissions!");
                }
            }

            //fine, folders are created. Now do the page procedure for default page
            $filePath = RPGMakerES::getRootfolder("public/" . $currentPath . "/index." . ($mode == WebGenerator::$DYNAMIC ? "php" : "html"));
            if ((!@file_exists($filePath) || $force ) && !$childForcing || $childForcing == WebGenerator::$DEFAULT_CHILD_ALIAS ) {
                switch ($mode) {
                    case WebGenerator::$DYNAMIC:
                        WebGenerator::createDyn($filePath, $page, $default_method[0], $default_method[1]);
                        break;
                    case WebGenerator::$STATIC:
                        WebGenerator::createStatic($filePath, $page, $default_method[0], $default_method[1]);
                        break;
                }
                //if my intention is just generate the default child then I'm done.
                if ($childForcing == WebGenerator::$DEFAULT_CHILD_ALIAS ) return;

            } else if ($mode == WebGenerator::$STATIC && !!$timeout ) {
                //I can calculate timeout for this one
                $lastModified = filemtime($filePath);
                if (!$lastModified) {
                    if (!RPGMakerES::isBrowser()) echo "WARNING: Unable to gather last modified date of file: " . $filePath . PHP_EOL;
                } else {
                    $current_time = time();
                    if ($current_time - $lastModified > ($timeout * 60) ) {
                        //time is out, renew it
                        WebGenerator::createStatic($filePath, $page, $default_method[0], $default_method[1]);
                    }
                }
            }

            //any children?
            foreach ($children as $child) {
                //build folder path and check it
                if (!@is_dir(RPGMakerES::getRootfolder("public/" . $currentPath . $child[0]))) {
                    //attempt to create it
                    $out = mkdir(RPGMakerES::getRootfolder("public/" . $currentPath . $child[0]));
                    if (!$out) throw new Exception("Unable to create folder: " . RPGMakerES::getRootfolder("public/" . $currentPath . $child[0]) . ". Check file permissions!");
                }

                //create child
                $filePath = RPGMakerES::getRootfolder("public/" . $currentPath . $child[0] . "/index." . ($mode == WebGenerator::$DYNAMIC ? "php" : "html"));
                if (!@file_exists($filePath) || $force) {
                    //if there's no child forcing, continue. But if there's child forcing, just continue if it's the chosen one.
                    if (!$childForcing || (!!$childForcing && $childForcing == $child[0])) {
                        switch ($mode) {
                            case WebGenerator::$DYNAMIC:
                                WebGenerator::createDyn($filePath, $page, $child[1], $child[2]);
                                break;
                            case WebGenerator::$STATIC:
                                WebGenerator::createStatic($filePath, $page, $child[1], $child[2]);
                                break;
                        }
                        //if there's child forcing, then I'm done.
                        if (!!$childForcing) return;
                    }
                } else if ($mode == WebGenerator::$STATIC && !!$timeout ) {
                    //I can calculate timeout for this one
                    $lastModified = filemtime($filePath);
                    if (!$lastModified) {
                        if (!RPGMakerES::isBrowser()) echo "WARNING: Unable to gather last modified date of file: " . $filePath . PHP_EOL;
                    } else {
                        $current_time = time();
                        if ($current_time - $lastModified > ($timeout * 60) ) {
                            //time is out, renew it
                            WebGenerator::createStatic($filePath, $page, $child[1], $child[2]);
                        }
                    }
                }
            }

        } else {
            if (!RPGMakerES::isBrowser()) echo "WARNING: Unable to locate controller in routes.php with name: " . $page . PHP_EOL;
        }
    }

    /**
     * Generates a PHP file for a page of a dynamic cntroller
     * @param String $file filename to write out file
     * @param String $controller Controller name ID, as defined in routes.php
     * @param String $method Method name in the controller to be called.
     * @param mixed $parameter Optional, any literal parameter to pass to the function.
     */
    static function createDyn($file, $controller, $method, $parameter = null)
    {
        if (!RPGMakerES::isBrowser()) echo $file . PHP_EOL;

        //generate output for the dynamic file
        $contents = "<?php" . PHP_EOL .
            "/*" . PHP_EOL .
            " * This file is part of RPG Maker ES Core" . PHP_EOL .
            " * (c) RPG Maker ES community." . PHP_EOL .
            " * This code is licensed under MIT license (see LICENSE for details)" . PHP_EOL .
            "*/" . PHP_EOL .
            "" . PHP_EOL .
            "//THIS FILE IS GENERATED AUTOMATICALLY. DO NOT EDIT! (you will lose the changes in the next update)" . PHP_EOL .
            "" . PHP_EOL .
            "include_once \$_SERVER['DOCUMENT_ROOT'] . \"/../core/main.php\";" . PHP_EOL .
            "echo RPGMakerES::generateDyn(\"" . $controller . "\", \"" . $method . "\", " .
            ($parameter == null ? "null" : "\"" . $parameter . "\"") . ");" . PHP_EOL .
            "" . PHP_EOL;

        //write the content to the file
        if (file_put_contents($file, $contents) === FALSE) {
            if (!RPGMakerES::isBrowser()) echo "ERROR: Unable to write at " . $file . PHP_EOL;
        }
    }

    /**
     * Generates an pre-rendered HTML file from a page of a static Controller.
     * @param String $file filename to write out file
     * @param String $controller Controller name ID, as defined in routes.php
     * @param String $method Method name in the controller to be called.
     * @param mixed $parameter Optional, any parameter to pass to the function to render it to.
     */
    static function createStatic($file, $controller, $method, $parameter = null) {

        include_once "ViewProcessor.php";

        if (!RPGMakerES::isBrowser()) echo $file . PHP_EOL;

        //generate output
        $contents = "<!-- THIS FILE IS GENERATED AUTOMATICALLY. DO NOT EDIT! (you will lose the changes in the next update) -->" . PHP_EOL;
        $contents.= ControllerSolver::call($controller, $method, $parameter);

        //write the content to the file
        if (file_put_contents($file, $contents) === FALSE) {
            if (!RPGMakerES::isBrowser()) echo "ERROR: Unable to write at " . $file . PHP_EOL;
        }
    }

    /**
     * Auxilliary function that recursive deletes a folder and it's contents.
     * Original author: nbari at dalmp dot com, from https://www.php.net/manual/es/function.rmdir.php#110489
     * @param String $dir The directory path to be deleted
     * @return bool True or false if it's deleted or not.
     */
    static function _delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? WebGenerator::_delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}
