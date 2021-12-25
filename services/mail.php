<?php defined('RPGMAKERES') OR exit();

/*
 * This file is part of RPG Maker ES Core
 * (c) RPG Maker ES community.
 * This code is licensed under MIT license (see LICENSE for details)
*/

/**
 * Class MailService
 * Class for mail sending
 */
class MailService {

    /**
     * Sends a mail to the desired destination, with a renderered view and its parameters.
     * @param String $subject Title of the mail
     * @param String $toName Name of destination
     * @param String $toMail Mail address of destination
     * @param String $viewName View filename to load
     * @param array $contextVariables Key-value array of parameters of the view
     * @return bool True or false if the mail was sent correctly or not. If not, more detailed data is written to the log.
     */
    public static function sendByView($subject, $toName, $toMail, $viewName, $contextVariables)
    {
        include_once  RPGMakerES::GetRootFolder("core/ViewProcessor.php");
        $output = strip_tags(ViewProcessor::renderHTML($viewName, $contextVariables));

        return MailService::sendRaw($subject, $toName, $toMail, $output);
    }

    /**
     * Sends a mail to the desired destination, with the specified body
     * @param String $subject Title of the mail
     * @param String $toName Name of destination
     * @param String $toMail Mail address of destination
     * @param String $body Body of the email
     * @return bool True or false if the mail was sent correctly or not. If not, more detailed data is written to the log.
     */
    public static function sendRaw($subject, $toName, $toMail, $body)
    {
        global $_RPGMAKERES;

        //sending mail
        mb_language("en");
        $to = mb_encode_mimeheader('"' . trim($toName) . '" <' . strtolower(trim($toMail)) . '>');
        $subject = mb_encode_mimeheader($subject);
        $headers = mb_encode_mimeheader("From: \"{$_RPGMAKERES["config"]["mailFromName"]}\" <{$_RPGMAKERES["config"]["mailFromAddress"]}>");
        if (!mb_send_mail($to, $subject, $body, $headers)) {
            RPGMakerES::log("Error while sending mail to $toMail: " . print_r(error_get_last(), true));
            return false;
        }

        return true;
    }

}
