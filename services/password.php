<?php defined('RPGMAKERES') OR exit();

/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

class PasswordService
{
    /**
     * It generates a password hash with Argon2ID algorithm
     * @param String $password_plain Original password in plain text
     * @return false|string|null The encoded generated password or false/null if error
     */
    public static function generatePassword( $password_plain ) {
        return password_hash ($password_plain, PASSWORD_ARGON2ID );
    }

    /**
     * Check if a given password matches with an password encoded value
     * @param String $password_plain The password in plain text to be checked
     * @param String $password_enc The already encoded password for using for plain password checking
     * @return bool True or false depending if password matches or not
     */
    public static function checkPassword( $password_plain, $password_enc ) {
        return password_verify ($password_plain , $password_enc);
    }

    /**
     * It generates a unique SHA2 hash , useful for different purposes
     * @param String|null $uniqueString Optional string to make a hash more unique.
     * @return string A generated hash
     */
    public static function generateToken( $uniqueString = NULL ) {
        if (!$uniqueString) $uniqueString = rand(0,100);
        return hash ( "sha512" , date("U") . $uniqueString);
    }

}
