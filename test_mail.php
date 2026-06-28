<?php

include("includes/mail.php");

$result = sendMail(
    "nbro62185@gmail.com",
    "PHPMailer Test",
    "<h2>Congratulations!</h2>
     <p>Your Room Rental System email is working successfully.</p>"
);

if($result){
    echo "✅ Email sent successfully!";
}else{
    echo "❌ Failed to send email.";
}