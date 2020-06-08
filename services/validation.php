<?php defined('RPGMAKERES') OR exit();
/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class ValidationService
 * Functions for misc primitive objects validation and cleanup
 */
class ValidationService
{

    /**
     * Clean a string by trimming, escaping special characters and limitng their size.
     * @param string $data Input string
     * @param int $length Desired length of the string
     * @return string The cleanized string
     */
    public static function cleanString($data, $length)
    {
        $data = substr($data, 0, $length);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * Clean a int-casted number by filtering any non-digit character of it.
     * @param mixed $i Input number-like data.
     * @return bool|int A sanitized number, false if there's no number on input.
     */
    public static function cleanNumber($i)
    {
        if (is_numeric($i)) return ( int )$i;
        else return False;
    }

    /**
     * Checks if provided string is valid (if it's clean) or not.
     * @param string $data Input string
     * @param int $length Length of the string
     * @return bool True or false if string is valid.
     */
    public static function isValidString($data, $length)
    {
        if (!strcmp($data, ValidationService::cleanString($data, $length)) && preg_match('/^[\w\-]+$/', $data) == 1) return true;
        return false;
    }

    /**
     * Checks if provided number-like data it's a valid number or not.
     * @param mixed $i Input number-like data
     * @return bool True or false if number-like data is valid.
     */
    public static function isValidNumber($i)
    {
        if (is_numeric(ValidationService::cleanNumber($i))) return true;
        return false;
    }
}