<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ----------------------
// SECURITY HEADERS
// ----------------------
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed");
}

// ----------------------
// SANITIZE INPUT
// ----------------------
$fullName = trim($_POST['fullName'] ?? '');
$email    = trim($_POST['email'] ?? '');
$company  = trim($_POST['company'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$subject  = trim($_POST['subject'] ?? '');
$message  = trim($_POST['message'] ?? '');

if (!$fullName || !$email || !$subject || !$message) {
    http_response_code(400);
    exit("Missing required fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit("Invalid email address.");
}

// ----------------------
// LOAD PEAR MAIL
// ----------------------
require_once "Mail.php";

if (!class_exists('Mail')) {
    http_response_code(500);
    exit("PEAR Mail not available on server.");
}

// ----------------------
// SMTP CONFIG (GMAIL)
// ----------------------
$smtpHost = "ssl://smtp.gmail.com";
$smtpPort = 465;

// CHANGE THESE 👇
$smtpUser = "htarhini@nuwavp.com";
$smtpPass = "qhxa rlkm yymc kkld"; // Gmail App Password

$headers = [
    "From"      => "DAIS Website <{$smtpUser}>",
    "To"        => "info@dais-global.com",
    "Subject"   => "New Contact Form Message: {$subject}",
    "Reply-To"  => "{$fullName} <{$email}>",
    "Content-Type" => "text/plain; charset=UTF-8"
];

$body =
"You received a new message from the website contact form:\n\n" .
"Full Name: {$fullName}\n" .
"Email: {$email}\n" .
"Company: {$company}\n" .
"Phone: {$phone}\n\n" .
"Message:\n{$message}\n";

// ----------------------
// SEND MAIL
// ----------------------
$mail = Mail::factory("smtp", [
    "host"     => $smtpHost,
    "port"     => $smtpPort,
    "auth"     => true,
    "username" => $smtpUser,
    "password" => $smtpPass
]);

$result = $mail->send("info@dais-global.com", $headers, $body);

if (PEAR::isError($result)) {
    error_log("Mail error: " . $result->getMessage());
    http_response_code(500);
    exit("Message could not be sent.");
}

echo "OK";
exit;
