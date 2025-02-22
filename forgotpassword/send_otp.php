<?php
session_start();
require '../register_and_login/dbconnection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $_session['fpEmail'] = $email;
    //check email in voter at first
    $sql1 = "SELECT email, citizenshipNumber FROM voters WHERE email = '$email'";
    $result1 = mysqli_query($conn, $sql1);

    $sql2 = "SELECT email, citizenshipNumber FROM pendingVoters WHERE email = '$email'";
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result1) > 0) {
        $message = "Your password reset OTP is:";
        sendOtp($message, $email);
    } else if (mysqli_num_rows($result2) > 0) {
        $_SESSION['pending'] = true;
        $message = "Your account is on pending status.Your password reset OTP is:";
        sendOtp($message, $email);
    } else {
        $_SESSION['error_message'] = "Email not resgistered";
        header('Location: ../forgotpassword/forgot_password.php');
    }
    
} else {
    header("Location: ../forgotpassword/forgot_password.php");
    exit();
}
function sendOtp($body, $email)
    {
        // Generate OTP
        $otp = rand(100000, 999999);
        // Save email in session
        $_SESSION['fpEmail'] = $email;
        // Save OTP in a cookie for 5 minutes
        setcookie("otp", $otp, time() + 300, "/");


        require '../PHPMailer-master/src/PHPMailer.php';
        require '../PHPMailer-master/src/SMTP.php';
        require '../PHPMailer-master/src/Exception.php';

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
        $mail->setFrom('sijalneupane5@gmail.com', 'Digital Matadan Pranali');
        // $mail->addAddress($_POST['email'], $_POST['name']); // User's email and name
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Reset Password';
        $mail->Body = 'Hello ' . htmlspecialchars($email) . ',<br><br>' . $body . ':<br><strong>' . $otp . '</strong><br><br> It is valid for 5 minutes only and dont share with others. Ignore if you did not requested. .<br><br>Best Regards,<br>Digital Matadan Pranali';

        // Send the email and check for errors
        if ($mail->send()) {
            header("Location: ../forgotpassword/enter_otp.php");
        } else {
            echo "However, email sending failed. Error: " . $mail->ErrorInfo;
        }
    }
?>
