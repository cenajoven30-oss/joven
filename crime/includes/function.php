<?php
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

function sendCrimeAlert($to, $type, $location) {
    $subject = "New Crime Reported: $type";
    $message = "A new crime has been reported at $location. Please review the case.";
    $headers = "From: alerts@crime-tracker.com";

    mail($to, $subject, $message, $headers);
}
?>