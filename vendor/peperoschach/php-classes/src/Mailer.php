<?php

namespace Peperoschach;

use Rain\Tpl;

class Mailer {

    // Enter your email settings here

    //Username to use for SMTP authentication - use full email address
    const USERNAME = "";

    //Password to use for SMTP authentication
    const PASSWORD = "";

    //Set who the message is to be sent from
    const NAME_FROM = "Store";

    private $mail;

    public function __construct($toAddress, $toName, $subject, $tplName, $data  = array())
    {
        $config = array(
            "base_url"      => null,
            "tpl_dir"       => $_SERVER['DOCUMENT_ROOT'] . "/ecommerce/views/email/",
            "cache_dir"     => $_SERVER['DOCUMENT_ROOT'] . "/ecommerce/views-cache/",
            "debug"         => false
        );
        Tpl::configure($config);
        $tpl = new Tpl();
        foreach ($data as $key => $value) {
            $tpl->assign($key, $value);
        }
        $html = $tpl->draw($tplName, true);
        $this->mail = new \PHPMailer;
        $this->mail->SMTPDebug = 0;
        $this->mail->Debugoutput = 'html';
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Port = 587;
        $this->mail->isSMTP();
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $this->mail->SMTPSecure = 'tls';

        $this->mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $this->mail->Username = Mailer::USERNAME;

        //Password to use for SMTP authentication
        $this->mail->Password = Mailer::PASSWORD;

        //Set who the message is to be sent from
        $this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);

        //Set who the message is to be sent to
        $this->mail->AddAddress($toAddress, $toName);

        //Set the subject line
        $this->mail->Subject = $subject;

        $this->mail->msgHTML($html);

        $this->mail->AltBody = 'This is a plain-text message body';
    }

    public function send()
    {
        return $this->mail->send();
    }
}
