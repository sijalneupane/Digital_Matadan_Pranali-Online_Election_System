<?php
// Include PHPMailer library files
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
function sendMail($recieverEmail, $recieverName,$subject,$body){
    
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
$mail->addAddress($recieverEmail, $recieverName);

$mail->isHTML(true);
$mail->Subject = '<strong>'.$subject.'</strong>';
$mail->Body = 'Dear '. htmlspecialchars($recieverName).'</strong>'. $body;

// Send the email and check for errors
if ($mail->send()) {
    echo $subject . "email is sent";
} else {
    echo "Email sending failed. Error: " . $mail->ErrorInfo;
}
}
sendMail('neupaneboss5@gmail.com', "isjal", "Voter registration successfull", "You account is verified ");
?>
