<?php
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/
include_once "core/main.php";

if (RPGMakerES::isBrowser()) exit();
global $_RPGMAKERES;

$options = getopt("hvufw:", ["help", "version", "update", "force", "write:"]);

echo "RPG Maker ES Core version " . $_RPGMAKERES["config"]["version"] . PHP_EOL;

if (array_key_exists("v", $options) || array_key_exists("version", $options)) {
    exit(0);
}

if (array_key_exists("h", $options) || array_key_exists("help", $options)) {

    echo
        "RPG Maker ES Core usage:" . PHP_EOL . PHP_EOL .
        "-v     --version                       Display version and exit" . PHP_EOL .
        "-h     --help                          Display this help and exit" . PHP_EOL .
        "-u     --update                        Update all pending sections of the page if needed" . PHP_EOL .
        "-f     --force                         If enabled, all sections will be updated, regardless if these are up-to-date" . PHP_EOL .
        "-w=CONTROLLER     --write=CONTROLLER   Writes a single section of the site, specified by a CONTROLLER name" . PHP_EOL . PHP_EOL .
        "We still didn't know what the hell Entidad2 is.";

    exit(0);
}

$force = false;
if (array_key_exists("f", $options) || array_key_exists("force", $options)) {
    echo "Force option is activated" . PHP_EOL;
    $force = true;
}

if (array_key_exists("u", $options) || array_key_exists("update", $options)) {

    include_once "core/WebGenerator.php";

    echo "Updating ... " . PHP_EOL;
    WebGenerator::generate($force);
    echo PHP_EOL . "Done!" . PHP_EOL;
    exit(0);
}

if (array_key_exists("w", $options) || array_key_exists("write", $options)) {

    include_once "core/WebGenerator.php";

    $value = "";
    if (array_key_exists("w", $options)) $value = $options["w"];
    if (array_key_exists("write", $options)) $value = $options["write"];

    echo "Updating ... " . PHP_EOL;
    if (WebGenerator::generateSingle($value, $force)) echo PHP_EOL . "Done!" . PHP_EOL;
    else echo PHP_EOL . "Controller not found" . PHP_EOL;
    exit(0);
}

echo "Invalid option. Type php cron.php -h for usage";
exit(1);
