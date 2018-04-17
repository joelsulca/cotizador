<?php

/**
 * Created by PhpStorm.
 * User: holivares
 * Date: 4/17/2018
 * Time: 2:50 PM
 */
class Sendgridci
{

    public function __construct($params = [])
    {

    }
    public function sendMail($from_mail, $to_mail, $subject, $message)
    {
        $from = new SendGrid\Email(null, $from_mail);
        $to = new SendGrid\Email(null, $to_mail);
        $content = new SendGrid\Content("text/plain", $message);
        $mail = new SendGrid\Mail($from, $subject, $to, $content);

        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);
        return $response->statusCode() === 202;
    }

    public function sendHtmlMail($from_mail, $to_mail, $subject, $message)
    {
        $from = new SendGrid\Email(null, $from_mail);
        $to = new SendGrid\Email(null, $to_mail);
        $content = new SendGrid\Content("text/html", $message);
        $mail = new SendGrid\Mail($from, $subject, $to, $content);

        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);

        $response = $sg->client->mail()->send()->post($mail);
        return $response->statusCode() === 202;
    }
}