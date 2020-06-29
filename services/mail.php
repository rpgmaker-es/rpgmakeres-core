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
     * Instance of Swift_Mailer, for multiple using.
     * @var Swift_Mailer
     */
    private static $mailer = NULL;

    /**
     * Configures and connects to the mail server, with the credentials set in configuration.
     */
    public static function connect()
    {
        global $_RPGMAKERES;

        if (!MailService::$mailer) {
            $transport = (new Swift_SmtpTransport($_RPGMAKERES["config"]["smtpAddress"], $_RPGMAKERES["config"]["smtpPort"]));
            if ($_RPGMAKERES["config"]["smtpUseLoginInfo"]) {
                $transport->setUsername($_RPGMAKERES["config"]["smtpUser"])->setPassword($_RPGMAKERES["config"]["smtpPassword"]);
            }

            MailService::$mailer = new Swift_Mailer($transport);
            MailService::$mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin($_RPGMAKERES["config"]["mailTreshold"], $_RPGMAKERES["config"]["mailWaitInternal"]));
        }
    }

    /**
     * Sends a mail to the desired destination, with a renderered view and its parameters.
     * @param String $subject Title of the mail
     * @param String $toName Name of destination
     * @param String $toMail Mail address of destination
     * @param String $viewName View filename to load
     * @param array $contextVariables Key-value array of parameters of the view
     * @return bool True or false if the mail was sent correctly or not. If not, more detailed data is written to the log.
     * @throws \Soundasleep\Html2TextException
     */
    public static function sendByView($subject, $toName, $toMail, $viewName, $contextVariables)
    {
        include_once  RPGMakerES::GetRootFolder("core/ViewProcessor.php");
        $outputHTML = ViewProcessor::renderHTML($viewName, $contextVariables);
        $outputText = \Soundasleep\Html2Text::convert($outputHTML);

        return MailService::sendRaw($subject, $toName, $toMail, $outputText, $outputHTML);
    }

    /**
     * Sends a mail to the desired destination, with the specified body in text and HTML
     * @param String $subject Title of the mail
     * @param String $toName Name of destination
     * @param String $toMail Mail address of destination
     * @param String $bodyText Body of the email in plain text
     * @param String $bodyHTML Body of the email in HTML
     * @return bool True or false if the mail was sent correctly or not. If not, more detailed data is written to the log.
     */
    public static function sendRaw($subject, $toName, $toMail, $bodyText, $bodyHTML)
    {
        global $_RPGMAKERES;

        if (!MailService::$mailer) MailService::connect();

        //preparing mail
        try {
            $message = new Swift_Message($subject);
            $message->setFrom([$_RPGMAKERES["config"]["mailFromAddress"] => $_RPGMAKERES["config"]["mailFromName"]])
                ->setReturnPath($_RPGMAKERES["config"]["mailFromAddress"])
                ->setTo([strtolower(trim($toMail)) => trim($toName)])
                ->setBody($bodyHTML, 'text/html')
                ->addPart($bodyText, 'text/plain');
        } catch(Exception $e) {
            RPGMakerES::log("Error while building mail to " . $toMail . ": " . $e->getMessage());
            return false;
        }

        //sending mail
        $failedRecipients = []; //this is just a reference array for writing failed recipients
        try {
            MailService::$mailer->send($message);
        } catch (Exception $e) {
            RPGMakerES::log("Error while sending mail to " . $toMail . ": " . $e->getMessage());
            return false;
        }
        if (!empty($failedRecipients)) {
            RPGMakerES::log("Failed recipient while sending to " . $toMail . ": ");
            return false;
        }

        return true;
    }

}