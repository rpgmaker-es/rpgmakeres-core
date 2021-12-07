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
     * Instance of Mailer, for multiple using.
     * @var \Symfony\Component\Mailer\Mailer
     */
    private static $mailer = NULL;

    /**
     * Configures and connects to the mail server, with the credentials set in configuration.
     */
    public static function connect()
    {
        global $_RPGMAKERES;

        if (!MailService::$mailer) {
            if ($_RPGMAKERES["config"]["smtpUseLoginInfo"]) {
                $transport = \Symfony\Component\Mailer\Transport::fromDsn('smtp://' . $_RPGMAKERES["config"]["smtpUser"] . ':' . $_RPGMAKERES["config"]["smtpPassword"] . '@' . $_RPGMAKERES["config"]["smtpAddress"] . ':' . $_RPGMAKERES["config"]["smtpPort"]);
            } else {
                $transport = \Symfony\Component\Mailer\Transport::fromDsn('native://default');
            }

            MailService::$mailer = new \Symfony\Component\Mailer\Mailer($transport);
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
            $message = (new \Symfony\Component\Mime\Email())
                ->subject($subject)
                ->from(new \Symfony\Component\Mime\Address($_RPGMAKERES["config"]["mailFromAddress"], $_RPGMAKERES["config"]["mailFromName"]))
                ->to(new \Symfony\Component\Mime\Address(strtolower(trim($toMail)), trim($toName)))
                ->html($bodyHTML)
                ->text($bodyText);
        } catch (Exception $e) {
            RPGMakerES::log("Error while building mail to " . $toMail . ": " . $e->getMessage());
            return false;
        }

        //sending mail
        try {
            MailService::$mailer->send($message);
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
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
