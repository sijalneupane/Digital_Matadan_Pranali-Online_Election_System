<?php
// // Include PHPMailer library files
// require '../PHPMailer-master/src/Exception.php';
// require '../PHPMailer-master/src/PHPMailer.php';
// require '../PHPMailer-master/src/SMTP.php';
// function sendMail($recieverEmail, $recieverName,$subject,$body){
    
// // Create a new PHPMailer instance
// $mail = new PHPMailer\PHPMailer\PHPMailer();

// // Configure SMTP settings
// $mail->isSMTP();
// $mail->Host = 'smtp.gmail.com';
// $mail->SMTPAuth = true;
// $mail->Username = 'sijalneupane5@gmail.com'; // Replace with your Gmail address
// $mail->Password = 'hhjr rvuh wkoa cqfr';   // Replace with your generated app password
// $mail->SMTPSecure = 'tls';               // Enable TLS encryption
// $mail->Port = 587;                       // TLS port is 587

// // Email content
// $mail->setFrom('sijalneupane5@gmail.com', 'Digital Matadan Pranali');
// // $mail->addAddress($_POST['email'], $_POST['name']); // User's email and name
// $mail->addAddress($recieverEmail, $recieverName);

// $mail->isHTML(true);
// $mail->Subject = $subject;
// $mail->Body = 'Dear '. htmlspecialchars($recieverName). $body;

// // Send the email and check for errors
// if ($mail->send()) {
//     // echo $subject . "email is sent";
//     return true;
// } else {
//     // echo "Email sending failed. Error: " . $mail->ErrorInfo;
//     return false;
// }
// }
// Include PHPMailer library files
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

//using abstract api to verify if the reciever email address is valid or not
function verifyEmail($email) {
    $apiKey = '91b5e9bbcf3a43b58abe9d19ab7b7704'; // Replace with your Abstract API key
    $apiUrl = "https://emailvalidation.abstractapi.com/v1/?api_key=$apiKey&email=$email";

    // Get the API response
    $response = file_get_contents($apiUrl);
    $result = json_decode($response, true);

    // Check if the email is valid based on deliverability
    if (isset($result['deliverability']) && $result['deliverability'] === 'DELIVERABLE') {
        return true; // Email is valid
    }
    return false; // Invalid email
}

function sendMail($recieverEmail, $recieverName, $subject, $body) {
    // Validate Email Address using API
    if (!verifyEmail($recieverEmail)) {
        // error_log("Invalid email address: $recieverEmail");
        return false; // Stop if email is not valid
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer\PHPMailer\PHPMailer();

    // Configure SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'sijalneupane5@gmail.com'; // Replace with your Gmail address
    $mail->Password = 'hhjr rvuh wkoa cqfr';   // Replace with your Gmail app password
    $mail->SMTPSecure = 'tls';  // Enable TLS encryption
    $mail->Port = 587;  // TLS port is 587

    // Email content
    $mail->setFrom('sijalneupane5@gmail.com', 'Digital Matadan Pranali');
    $mail->addAddress($recieverEmail, $recieverName);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = 'Dear ' . htmlspecialchars($recieverName) . ', ' . $body;

    // Send the email and check for errors
    if ($mail->send()) {
        return true;
    } else {
        // error_log("Email sending failed. Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>
