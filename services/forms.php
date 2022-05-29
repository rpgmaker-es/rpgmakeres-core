<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class FormsService
 * Functions for helping forms screens.
 */
class FormsService
{
    public static function fill($element, $property) {
        if (array_key_exists($property, $element)) {
            return $element[$property];
        }
        return "";
    }

    public static function fillSelect($element, $property, $value) {
        if (array_key_exists($property, $element) && $element[$property] == $value) {
            return "selected";
        }
        return "";
    }

    public static function fillChecked($element, $property) {
        if (array_key_exists($property, $element) && $element[$property] == "1") {
            return "checked";
        }
        return "";
    }

    public static function isFilled($element) {
        return (count($element) > 0);
    }

    public static function replaceUserWithPreFilledData(&$data) {
        foreach (array_keys($_POST) as $key) {
            $data[$key] = $_POST[$key];
        }
    }
}