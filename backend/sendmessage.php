<?php
// ==== CONFIGURATION ====
// Replace with your email where messages should arrive:
$recipientEmail = "info@dais-global.com";

// ==== PROCESS FORM SUBMISSION ====
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Collect and sanitize fields
    $fullName = htmlspecialchars(trim($_POST['fullName'] ?? ''));
    $email    = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $company  = htmlspecialchars(trim($_POST['company'] ?? ''));
    $phone    = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $subject  = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message  = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Basic validation
    if (empty($fullName) || empty($email) || empty($subject) || empty($message)) {
        die("Please fill in all required fields.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // Build email content
    $emailSubject = "New Contact Form Message: " . $subject;

    $emailBody = "
        You received a new message from the website contact form:\n\n
        Full Name: {$fullName}\n
        Email: {$email}\n
        Company: {$company}\n
        Phone: {$phone}\n
        Subject: {$subject}\n\n
        Message:\n{$message}\n
    ";
    $headers = "From: Website <no-reply@dais-global.com>\r\n";


    // Send email
    if (mail($recipientEmail, $emailSubject, $emailBody, $headers)) {
        echo "Your message is sent. Our team will contact you as soon as possible"; // You can redirect instead
        // header("Location: thank-you.html");
        exit;
    } else {
        echo "Failed to send email. Please try again later.";
    }
} else {
    echo "Invalid request method.";
}
?>