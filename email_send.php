<?php
// Include PHPMailer library files
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Create a new PHPMailer instance
$mail = new PHPMailer\PHPMailer\PHPMailer();

// Configure SMTP settings
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'sijalneupane5@gmail.com'; // Replace with your Gmail address
$mail->Password = 'hhjr rvuh wkoa cqfr';   // Replace with your generated app password
$mail->SMTPSecure = 'tls';               // Enable TLS encryption
$mail->Port = 587;                       // TLS port is 587

// Email content
$mail->setFrom('sijalneupane5@gmail.com', 'sjnp.tech');
// $mail->addAddress($_POST['email'], $_POST['name']); // User's email and name
$mail->addAddress('neupaneboss180@gmail.com', 'Rammm ji');

$mail->isHTML(true);
$mail->Subject = 'Registration Successful';
$mail->Body = 'Hello ' . htmlspecialchars('Rammm ji') . ',<br><br>Thank you for registering on our website.<br><br>Best Regards,<br>Your Website Team';

// Send the email and check for errors
if ($mail->send()) {
    echo "Registration successful! A confirmation email has been sent.";
} else {
    echo "Registration successful! However, email sending failed. Error: " . $mail->ErrorInfo;
}
?>
