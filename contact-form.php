<?php
// Contact Form Handler for BuildCore Estimating
// Configure this file for your cPanel hosting

header('Content-Type: application/json');

// Email configuration
$to_email = "info@buildcoreestimating.com";
$from_email = "noreply@buildcoreestimating.com"; // Use your domain email
$subject = "New Contact Form Submission from BuildCore Estimating Website";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and validate input
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = strip_tags(trim($_POST["phone"]));
    $message = strip_tags(trim($_POST["message"]));
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
        exit;
    }
    
    // Email headers
    $headers = "From: BuildCore Estimating <$from_email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    // Email body
    $email_content = "
    <html>
    <head>
        <title>New Contact Form Submission</title>
    </head>
    <body>
        <h2>Contact Form Submission</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Message:</strong></p>
        <p>" . nl2br($message) . "</p>
    </body>
    </html>
    ";
    
    // Send email
    if (mail($to_email, $subject, $email_content, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'There was a problem sending your message. Please try again or contact us directly at info@buildcoreestimating.com']);
    }
} else {
    // Not a POST request
    echo json_encode(['success' => false, 'message' => 'There was a problem with your submission. Please try again.']);
}
?>
