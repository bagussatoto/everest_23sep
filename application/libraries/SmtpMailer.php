<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 25/05/18
 * Time: 21:23
 */
require_once "PHPMailer-master/src/Exception.php";
require_once "PHPMailer-master/src/PHPMailer.php";
require_once "PHPMailer-master/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SmtpMailer
{
    protected $subject;
    protected $address_from;
    protected $alias_from;
    protected $address_to;
    protected $alias_to;
    protected $reply_to;
    protected $cc_to;
    protected $bcc_to;

    //region geter-setter

    public function setAliasFrom($alias_from)
    {
        $this->alias_from = $alias_from;
    }

    public function setAliasTo($alias_to)
    {
        $this->alias_to = $alias_to;
    }

    public function setAddressFrom($address_from)
    {
        $this->address_from = $address_from;
    }

    public function setAddressTo($address_to)
    {
        $this->address_to = $address_to;
    }

    public function setBccTo($bcc_to)
    {
        $this->bcc_to = $bcc_to;
    }

    public function setCcTo($cc_to)
    {
        $this->cc_to = $cc_to;
    }

    public function setReplyTo($reply_to)
    {
        $this->reply_to = $reply_to;
    }


    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    //endregion

    function __construct()
    {
        $this->mail = new PHPMailer(true); // Passing `true` enables exceptions
    }

    public function kirim_email($text_html)
    {

        $mail = $this->mail;

        //die("mati");
        try {
            //region Server settings
            $mail->SMTPDebug = false; // Enable verbose debug output value:2
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            // $mail->Username = 'mgk.mailer.daemon@gmail.com'; // SMTP username
            // $mail->Password = 'aslkaslk'; // SMTP password
            $mail->Username = 'mgkcore@gmail.com'; // SMTP username
            // $mail->Username = 'noreply.mgkcore@gmail.com'; // SMTP username
            $mail->Password = 'rapirapi'; // SMTP password
            $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465; // TCP port to connect to TLS: 587 ssl: 465
            //endregion

            //Recipients
            /* ======================================================================================================
             penulisan alamat asal dengan alias bisa berbentuk aray
                $dari = array("BI-Softdrink" => "noreply@mayagrahakencana.com");
            =========================================================================================================
            */
            $add_from = "";
            if (is_array($this->address_from)) {
                foreach ($this->address_from as $alias_from => $add_from) {
                    $mail->setFrom($add_from, $alias_from); // 'from@example.com', 'Mailer'
                }
            }
            else {
                $add_from = $this->address_from;
                $alias_from = $this->alias_from;
                $mail->setFrom($add_from, $alias_from); // 'from@example.com', 'Mailer'
            }

            /* ======================================================================================================
            Untuk pengiriman dengan alias dari email tujuan alamat bisa diaraikan spt berikut, suport multy email
                $tujuan = array(
                    "Paklik Mas" => "thomas.jogja@gmail.com",
                );
                $tujuan = array(
                    "Paklik Mas" => "thomas.jogja@gmail.com",
                    "pakde" => "namakamoe@gmail.com"
                );
            =========================================================================================================
            */
            if (is_array($this->address_to)) {
                foreach ($this->address_to as $key_alias_to => $val_add_to) {
                    $add_to = $val_add_to;
                    $alias_to = $key_alias_to;
                    $mail->addAddress($add_to, $alias_to); // Add a recipient 'thomas.jogja@gmail.com', 'Joe User'
                }
            }
            else {
                $add_to = $this->address_to;
                $alias_to = $this->alias_to;
                $mail->addAddress($add_to, $alias_to); // Add a recipient 'thomas.jogja@gmail.com', 'Joe User'
            }

            //            $mail->addAddress('ellen@example.com');               // Name is optional
            if (isset($this->reply_to)) {
                $reply_to = $this->reply_to;
                $mail->addReplyTo($reply_to); //'info@example.com', 'Information'
            }
            else {
                $mail->addReplyTo($add_from); //'info@example.com', 'Information'
            }

            if (isset($this->cc_to)) {
                $mail->addCC($this->cc_to);
            }
            if (isset($this->bcc_to)) {
                $mail->addBCC($this->bcc_to);
            }

            //Attachments
            //    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $this->subject;
            $mail->Body = $text_html;
            $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';

            $mail->send();

            //            echo 'Message has been sent';
            return 1;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;

            return 0;
        }
    }
}