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
     * @param mixed $rangeStart Optional, the number will be valid if its equal or major than this number
     * @param mixed $rangeEnd Optional, the number will be valid if its equal or minor than this number
     * @return bool True or false if number-like data is valid.
     */
    public static function isValidNumber($i, $rangeStart = NULL, $rangeEnd = NULL)
    {
        $valid = false;
        if (is_numeric(ValidationService::cleanNumber($i))) {
            $valid = true;
        }

        if ($rangeStart) {
            if ($i < $rangeStart) {
                $valid = false;
            }
        }

        if ($rangeEnd) {
            if ($i > $rangeEnd) {
                $valid = false;
            }
        }

        return $valid;
    }

    public static function isValidEmail($data, $length) {
        if (filter_var($data, FILTER_VALIDATE_EMAIL) !== false ) return true;
        return false;
    }

    public static function isValidPassword($data, $length) {
        //TODO validate password settings to strong one
        if (self::isValidString($data, $length) && strlen($data) >=6) return true;
        return false;
    }

    /**
     * Returns if the data is not an empty string and/or NULL
     * @param $data
     * @return bool
     */
    public static function isFilled($data) {
        return $data!=NULL && (strlen($data) >0 );
    }

    /**
     * Validates if all values in array are valid keys, specified if they are in a possible values list.
     * @param array $input An key=>value array
     * @param array $possibleValues An array of possible key values (or just keys). If a key does not exist here, then the validation will fail.
     * @param bool $isOrderBy Specifies if keys are part of a order-by, so we can strip these values before validating.
     * @return bool True or false depending if the keys are valid or not.
     */
    public static function areValidKeys($input, $possibleValues, $isOrderBy = false) {

        //it's a sequential array? (non key-value) ?
        if (array_values($input) === $input) {
            //it's a sequential array
            foreach($input as $key) {
                if ($isOrderBy) {
                    //if it's an order by, I will remove the DESC strings first
                    $key = str_replace(" DESC", "", $key);
                    $key = str_replace(" desc", "", $key);
                }
                //all keys must be in the possible values array
                if (!in_array($key, $possibleValues)) {
                    //one key do not belong here. Stop!
                    return false;
                }
            }
        } else {
            //it's a key-value array
            foreach($input as $key => $value) {
                if ($isOrderBy) {
                    //if it's an order by, I will remove the DESC strings first
                    $key = str_replace(" DESC", "", $key);
                    $key = str_replace(" desc", "", $key);
                }
                //all keys must be in the possible values array
                if (!in_array($key, $possibleValues)) {
                    //one key do not belong here. Stop!
                    return false;
                }
            }
        }


        //Alright!
        return true;
    }

    public static $error_list = [];

    public static function addError($item, $description) {
        array_push(ValidationService::$error_list, [$item, $description]);
    }

    public static function hasErrors() {
        return count(ValidationService::$error_list) > 0;
    }

    public static function printErrors() {

        $out = "<ul class='validation_error'>\n";

        foreach(ValidationService::$error_list as $error) {
            $out .="<li>" . $error[1] . "</li>\n";
        }

        return $out . "\n";
    }

    public static function addClassIfError($property) {
        foreach(ValidationService::$error_list as $error) {
            if ($error[0] == $property) {
                return "error-class";
            }
        }
        return "";
    }

    public static function cleanError() {
        ValidationService::$error_list = [];
    }


}