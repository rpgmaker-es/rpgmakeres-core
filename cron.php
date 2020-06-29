<?php
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/
include_once "core/main.php";

if (RPGMakerES::isBrowser()) exit();
global $_RPGMAKERES;

$options = getopt("hvufw:d:m:", ["help", "version", "update", "force", "write:", "delete:", "mailtest:"]);

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
        "-w=CONTROLLER     --write=CONTROLLER   Writes a single section of the site, specified by a CONTROLLER name" . PHP_EOL .
        "-d=CONTROLLER     --delete=CONTROLLER  Deletes pages from a controller, specified by a CONTROLLER name" . PHP_EOL .
        "-m=EMAIL          --mailtest=EMAIL     Test email sending, by sending an email to EMAIL address" . PHP_EOL . PHP_EOL .
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

if (array_key_exists("d", $options) || array_key_exists("delete", $options)) {

    include_once "core/WebGenerator.php";

    $value = "";
    if (array_key_exists("d", $options)) $value = $options["d"];
    if (array_key_exists("delete", $options)) $value = $options["delete"];

    echo "Deleting ... " . PHP_EOL;

    if (WebGenerator::deleteControllerPages($value)) echo PHP_EOL . "Done!" . PHP_EOL;
    else echo PHP_EOL . "Controller not found" . PHP_EOL;
    exit(0);
}

if (array_key_exists("m", $options) || array_key_exists("mailtest", $options)) {

    RPGMakerES::loadService("mail");

    $value = "";
    if (array_key_exists("m", $options)) $value = $options["m"];
    if (array_key_exists("mailtest", $options)) $value = $options["mailtest"];

    echo "Sending test mail to ". $value . " ... " . PHP_EOL;

    $out = MailService::sendByView(
        "RPGMakerES Core mail sending",
        "Destination user",
        $value,
        "testMail.php",
        [
            "mail" => $value,
            "randomic" => rand(0,100)
        ]
    );

    if ($out) {
        echo "Done!" . PHP_EOL;
    } else {
        echo "Error while sending. Please see logs to find more information about." . PHP_EOL;
    }
    exit(0);
}

echo "Invalid option. Type php cron.php -h for usage";
exit(1);
