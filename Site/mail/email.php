<?php

// Helper method to send a email
function sendEmail($to, $subject, $message, $headers){
    if(mail($to, $subject, $message, $headers)){
        echo $message;
        return true;
    } else {
        echo 'not worked!';
        return false;
    }
}




?>