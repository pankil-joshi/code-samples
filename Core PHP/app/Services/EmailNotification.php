<?php

namespace App\Services;

use PHPMailer;
use Quill\Exceptions\BaseException;

class EmailNotification {

    protected $mail;

    public function __construct() {

        $this->mail = new \PHPMailer();
        $this->config = load_config_one('email');
    }

    public function sendMail($to, $subject, $html, $attachments = array()) {

        $this->mail->IsSMTP(); // telling the class to use SMTP
        $this->mail->Host = $this->config['host']; // SMTP server
//        $this->mail->SMTPDebug = 1;                     // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $this->mail->SMTPAuth = $this->config['SMTPAuth'];                  // enable SMTP authentication
        $this->mail->Port = $this->config['port'];                    // set the SMTP port for the GMAIL server
        $this->mail->SMTPSecure = $this->config['SMTPSecure'];
        $this->mail->Username = $this->config['username']; // SMTP account username
        $this->mail->Password = $this->config['password'];        // SMTP account password
        $this->mail->SetFrom($this->config['from_email'], $this->config['from_name']);
        $this->mail->Subject = $subject;

        if (!empty($this->config['bcc'])) {
            
            foreach ($this->config['bcc'] as $bcc) {

                $this->mail->AddBCC($bcc['email'], $bcc['name']);
            }
        }

        $this->mail->MsgHTML($html);
        if (!empty($to['reply_to_email'])) {
            $to['reply_to_name'] = !empty($to['reply_to_name']) ? $to['reply_to_name'] : '';
            $this->mail->AddReplyTo($to['reply_to_email'], $to['reply_to_name']);
        } else {

            $this->mail->AddReplyTo($this->config['default_reply_to_email'], $this->config['default_reply_to_name']);
        }
        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $this->mail->ClearAddresses();
        $this->mail->AddAddress($to['email'], $to['name']);

        if (!empty($attachments)) {

            foreach ($attachments as $attachment) {

                $this->mail->AddAttachment($attachment['path'], $attachment['name']);
            }
        }

        if (!$this->mail->Send()) {

            throw new BaseException($this->mail->ErrorInfo);
        } else {

            return true;
        }
    }

}
