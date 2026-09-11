<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// ========================================
// PHPMailer - Manual Installation
// ========================================

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';


// ========================================
// Email Settings
// ========================================

$to_email   = 'info@buildcoreestimating.com';
$from_email = 'info@buildcoreestimating.com';
$subject    = 'New Contact Form Submission - BuildCore Estimating';


// ========================================
// Only allow POST requests
// ========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);

    exit;
}


// ========================================
// Get Form Values
// ========================================

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');


// ========================================
// Validate Required Fields
// ========================================

if (empty($name) || empty($email) || empty($message)) {

    echo json_encode([
        'success' => false,
        'message' => 'Please fill in all required fields.'
    ]);

    exit;
}


// ========================================
// Validate Email
// ========================================

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        'success' => false,
        'message' => 'Please provide a valid email address.'
    ]);

    exit;
}


// ========================================
// Secure HTML Output
// ========================================

$name_safe = htmlspecialchars(
    $name,
    ENT_QUOTES,
    'UTF-8'
);

$email_safe = htmlspecialchars(
    $email,
    ENT_QUOTES,
    'UTF-8'
);

$phone_safe = htmlspecialchars(
    $phone,
    ENT_QUOTES,
    'UTF-8'
);

$message_safe = nl2br(
    htmlspecialchars(
        $message,
        ENT_QUOTES,
        'UTF-8'
    )
);


// ========================================
// Create PHPMailer
// ========================================

$mail = new PHPMailer(true);


try {

    // ========================================
    // Namecheap Private Email SMTP
    // ========================================

    $mail->isSMTP();

    $mail->Host = 'mail.privateemail.com';

    $mail->SMTPAuth = true;

    $mail->Username = 'info@buildcoreestimating.com';

    // Put your Namecheap Private Email password here
    $mail->Password = 'm@G!iK3kW:Sn9mY';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;


    // ========================================
    // Sender
    // ========================================

    $mail->setFrom(
        $from_email,
        'BuildCore Estimating Website'
    );


    // ========================================
    // Receiver
    // ========================================

    $mail->addAddress(
        $to_email,
        'BuildCore Estimating'
    );


    // ========================================
    // Reply To Customer
    // ========================================

    $mail->addReplyTo(
        $email,
        $name
    );


    // ========================================
    // Email Content
    // ========================================

    $mail->isHTML(true);

    $mail->Subject = $subject;

    $mail->Body = "
        <html>
        <body style=\"font-family: Arial, sans-serif; line-height: 1.6;\">

            <h2>New Contact Form Submission</h2>

            <hr>

            <p>
                <strong>Name:</strong><br>
                {$name_safe}
            </p>

            <p>
                <strong>Email:</strong><br>
                {$email_safe}
            </p>

            <p>
                <strong>Phone:</strong><br>
                {$phone_safe}
            </p>

            <p>
                <strong>Message:</strong><br>
                {$message_safe}
            </p>

            <hr>

            <p style=\"color:#666;font-size:12px;\">
                Sent from the BuildCore Estimating website contact form.
            </p>

        </body>
        </html>
    ";


    // ========================================
    // Plain Text Version
    // ========================================

    $mail->AltBody =
        "New Contact Form Submission\n\n" .
        "Name: " . $name . "\n" .
        "Email: " . $email . "\n" .
        "Phone: " . $phone . "\n\n" .
        "Message:\n" . $message;


    // ========================================
    // Send Email
    // ========================================

    $mail->send();


    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your message has been sent successfully.'
    ]);

} catch (Exception $e) {

    // Log actual SMTP error on server
    error_log(
        'BuildCore Contact Form Error: ' . $mail->ErrorInfo
    );

    echo json_encode([
        'success' => false,
        'message' => 'There was a problem sending your message. Please try again.'
    ]);
}

?>