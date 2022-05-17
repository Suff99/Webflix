<?php

// Helper method to send a email
function sendEmail($to, $subject, $message, $headers){
    if(mail($to, $subject, $message, $headers)){
        return true;
    } else {
        echo 'Email failed to send!';
        return false;
    }
}




?>