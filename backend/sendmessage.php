<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed");
}

/* ----------------------
   SANITIZE INPUT
---------------------- */
$fullName = trim($_POST['fullName'] ?? '');
$email    = trim($_POST['email'] ?? '');
$company  = trim($_POST['company'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$subject  = trim($_POST['subject'] ?? '');
$message  = trim($_POST['message'] ?? '');

if (!$fullName || !$email || !$subject || !$message) {
    http_response_code(400);
    exit("Missing required fields");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit("Invalid email");
}

/* ----------------------
   MAIL SETUP
---------------------- */
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    // 🔴 CHANGE THESE
    $mail->Username   = 'htarhini@nuwavp.com';
    $mail->Password   = 'YOUR_APP_PASSWORD';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->CharSet = 'UTF-8';
    $mail->SMTPDebug = 0;

    $mail->setFrom('htarhini@nuwavp.com', 'DAIS Website');
    $mail->addAddress('htarhini@nuwavp.com');
    $mail->addReplyTo($email, $fullName);

    $mail->isHTML(false);
    $mail->Subject = "New Contact Form Message: " . strip_tags($subject);

    $mail->Body =
        "You received a new message from the website contact form:\n\n" .
        "Full Name: $fullName\n" .
        "Email: $email\n" .
        "Company: $company\n" .
        "Phone: $phone\n\n" .
        "Message:\n$message\n";

    $mail->send();

    echo "OK";
    exit;

} catch (Exception $e) {
    error_log("Mailer Error: " . $mail->ErrorInfo);
    http_response_code(500);
    echo "Message could not be sent";
    exit;
}
