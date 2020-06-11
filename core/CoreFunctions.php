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
    static function getRootfolder($path = "")
    {
        return $_SERVER['DOCUMENT_ROOT'] . "/../" . $path;
    }


    /**
     * Calls a controller and returns the output of it.
     * @param String $controller Controller String ID, as defined in routes.php
     * @param String $function The function name in the controller.
     */
    static function generateDyn($controller, $function)
    {
        include_once "ControllerSolver.php";
        include_once "ViewProcessor.php";
        return ControllerSolver::call($controller, $function);

    }

    static function loadService($serviceName)
    {
        //Load service
        if (!@include_once(RPGMakerES::getRootFolder("/services/") . $serviceName . ".php")) {
            throw new Exception("The following service was not found: " . $serviceName);
        }
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
     * @param Exception $e An exception that caused this 500 error.
     * @return string The generated 500 error
     */
    static function gen500($e)
    {
        //write error in system log
        RPGMakerES::log("Caught 500 error: " . utf8_encode($e->getMessage()) . " File: " . utf8_encode($e->getFile()) . " Line: " . $e->getLine() . " Trace: " . utf8_encode(RPGMakerES::getCleanStackTrace($e)) );

        //outputs a 500 error
        return RPGMakerES::genFatalErrorOutput($e);
    }

    /**
     * Generates a stack trace string with censurated parameters. PHP versions before 7.4 generates stack traces with parameters, with the possibility of reveal sensible data.
     * @param Exception $e The exception that contains the stack trace
     * @return string A string with the generated stack trace
     */
    static function getCleanStackTrace($e) {
        $stacktrace = $e->getTrace();
        $out = "";
        for ($i=0; $i<count($stacktrace);$i++) {
            $out.= "#" . $i . " " . $stacktrace[$i]["file"] . "(" . $stacktrace[$i]["line"] . "):";
            if(array_key_exists("class", $stacktrace[$i])) {
                $out .= $stacktrace[$i]['class'] . $stacktrace[$i]['type'];
            }
            $out.= $stacktrace[$i]["function"] . "(";
            for ($j=0;$j<count($stacktrace[$i]["args"]); $j++) {
                $out.="_";
                if ($j != count($stacktrace[$i]["args"]) - 1) $out.=",";
            }
            $out .=")" . PHP_EOL;
        }
        return $out;
    }

    /**
     * Returns true if PHP is running from a browser, false if it's running via CLI/Cron
     * @return bool
     */
    static function isBrowser()
    {
        return (PHP_SAPI !== 'cli');
    }

    static function log($content) {
        error_log("[" . date("Y-m-d H:i:s") . "] " . $content, 0);
    }

    /**
     * Generates a HTTP error screen in HTML and prints error information if it's enabled.
     * @param Exception $e An exception to extract info.
     * @return string The generated 500 error
     */
    static function genFatalErrorOutput($e)
    {
        global $_RPGMAKERES;
        ob_start();
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <title>Major server failure lite</title>
            <style>
                body {
                    background-color: black;
                    color: white
                }

                h1 {
                    font-family: Impact, Charcoal, sans-serif;
                    font-style: italic;
                    font-variant: small-caps;
                    text-transform: uppercase;
                    font-size: 55px;
                }

                .desc {
                    font-family: sans-serif
                }

                .trace {
                    font-family: monospace
                }
            </style>
        </head>
        <body>
        <h1>500 MAJOR SERVER FAILURE</h1>
        <div class="desc">El servidor hizo weas y explotó.</div>
        <?php if ($_RPGMAKERES["config"]["showTracesIn500Error"]) {
            ?>
            <div class="trace">
                <br><br>
                Jarvik meditation:<br>
                <br>
                <?= utf8_encode($e->getMessage()) ?><br>
                In <?= utf8_encode($e->getFile()) ?> Line <?= $e->getLine() ?><br><br>
                Stack trace:<br>
                <?= nl2br(utf8_encode(RPGMakerES::getCleanStackTrace($e))) ?>
            </div>
            <?php
        }
        ?>
        </body>
        </html>
        <?php
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}