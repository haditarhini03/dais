<?php
// === CONFIG ===
$adminEmail = "info@dais-global.com";
$filePath   = __DIR__ . "/subscribers.txt";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "INVALID";
    exit;
}

$email = trim($_POST["email"] ?? "");

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "INVALID";
    exit;
}

// Create file if missing
if (!file_exists($filePath)) {
    file_put_contents($filePath, "");
}

// Read existing emails
$existing = array_map('strtolower', file(
    $filePath,
    FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
));

if (in_array(strtolower($email), $existing)) {
    echo "EXISTS";
    exit;
}

// Save email
file_put_contents($filePath, $email . PHP_EOL, FILE_APPEND);

// Notify admin
$subject = "New Newsletter Subscriber";
$body    = "New newsletter subscription:\n\nEmail: $email\n";
$headers = "From: DAIS Website <no-reply@dais-global.com>\r\n";

@mail($adminEmail, $subject, $body, $headers);

echo "OK";
exit;
