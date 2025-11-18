<?php
// === CONFIG ===
$adminEmail = "info@dais-global.com";    // Notifications are sent here
$filePath   = __DIR__ . "/subscribers.txt";  // File to store emails


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email.");
    }

    // Create file if missing
    if (!file_exists($filePath)) {
        file_put_contents($filePath, "");
    }

    // Check if email already exists
    $existing = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (in_array(strtolower($email), array_map('strtolower', $existing))) {
        echo "You are already subscribed.";
        exit;
    }

    // Save email
    file_put_contents($filePath, $email . PHP_EOL, FILE_APPEND);

    // Notify admin
    $subject = "New Newsletter Subscriber";
    $body = "A new user subscribed to the newsletter:\n\nEmail: $email\n";
    $headers = "From: Website <no-reply@yourdomain.com>\r\n";

    @mail($adminEmail, $subject, $body, $headers);

    // Response
    echo "Thank you for subscribing!"; 
    // You can redirect:
    // header("Location: ../thank-you-newsletter.html");
    exit;
}

echo "Invalid request.";
?>
