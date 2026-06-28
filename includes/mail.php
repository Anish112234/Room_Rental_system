<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendMail($to, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;

        // Gmail credentials
        $mail->Username = "rentalroom118@gmail.com";
        $mail->Password = "nkbl mycx jxol glvt";

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;//its make secure smpt connection
        $mail->Port = 587;

        $mail->setFrom(
            "rentalroom118@gmail.com",
            "RoomRental"
        );

        $mail->addAddress($to);

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();

        return true;

    } catch (Exception $e) {

        return false;

    }
}