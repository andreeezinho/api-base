<?php

namespace App\Infra\Services\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {

    private $mail;

    public function __construct(){
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Port = 587;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->SMTPAuth = true;
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Username = $_ENV['EMAIL'];
        $this->mail->Password = $_ENV['EMAIL_CODE'];
        $this->mail->setFrom($this->mail->Username, $_ENV['SITE_NAME']);
    }

    public function send(array $data){
        try{
            $this->mail->Subject = $data['subject'];

            $this->mail->addAddress($data['user_email'], $data['user_name']);

            $this->mail->Body = $data['email_text'];

            $this->mail->send();

            return true;

        }catch(Exception $e){
            return $e;
        }
    }

}