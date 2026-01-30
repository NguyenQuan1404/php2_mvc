<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailler
{

    public static function send($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // ===== SMTP CONFIG =====
            $mail->SMTPDebug = 0; // 0 = production, 2 = debug
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;


            $mail->Username = 'quannlpk04078@gmail.com';
            $mail->Password = 'hirvrdauaryykckz';


            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // ===== FROM =====
            $mail->setFrom("quannlpk04078@gmail.com", 'Ten Website');

            // ===== TO =====
            $mail->addAddress($to);

            // ===== CONTENT =====
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Lỗi gửi mail: {$mail->ErrorInfo}";
            return false;
        }
    }
}
