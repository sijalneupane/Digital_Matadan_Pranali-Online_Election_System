<?php
require 'dbconnection.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $name = $_POST['name'];
    $contact = $_POST['contactNo'];
    $email = $_POST['email'];
    $password = $_POST['password']; // In your case, no password hashing
    $district = $_POST['district'];
    $election_region = $_POST['regionNo'];
    $local_address = $_POST['local_address'];
    $citizenship_number = $_POST['citizenship_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $dob_converted = date('Y-m-d', strtotime($date_of_birth));

    $sameEmail = 0;
    $sameCitizenshipNo = 0;

    // Check if email or citizenship number already exists
    $sql1 = "SELECT email, citizenship_Number FROM voters";
    $result1 = mysqli_query($conn, $sql1);

    if (mysqli_num_rows($result1) > 0) {
        while ($row = mysqli_fetch_assoc($result1)) {
            if ($email == $row['email']) {
                $sameEmail = 1;
            }
            if ($citizenship_number == $row['citizenship_Number']) {
                $sameCitizenshipNo = 1;
            }
        }
    }

    if ($sameCitizenshipNo == 0) {
        if ($sameEmail == 0) {
            // SQL query to insert data using prepared statements
            $sql = "INSERT INTO voters (name, email, password, district, election_region, local_address, contact, date_of_birth, citizenship_number) 
                    VALUES ('$name', '$email', '$password', '$district', '$election_region', '$local_address', '$contact', '$dob_converted', '$citizenship_number')";

            if (mysqli_query($conn, $sql)) {
                // Get the Voter ID (the auto-incremented ID of the inserted row)
                $voter_id = mysqli_insert_id($conn);

                // Prepare email content
                $mail = new PHPMailer\PHPMailer\PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sijalneupane5@gmail.com'; // Replace with your Gmail address
                $mail->Password = 'hhjr rvuh wkoa cqfr';   // Replace with your generated app password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Email content
                $mail->setFrom('sijalneupane5@gmail.com', 'sjnp.tech');
                $mail->addAddress($email, $name); // Send to the registered email

                $mail->isHTML(true);
                $mail->Subject = 'Voter Registration Successful';
                $mail->Body = 'Hello ' . htmlspecialchars($name) . ',<br><br>Thank you for registering on our website.<br><br>Your Voter ID: ' . $voter_id . '<br><br>Best Regards,<br>Digital Matadan Pranali';

                // Send the email and check for errors
                if ($mail->send()) {
                    echo "<script>alert('Registration successful! A confirmation email has been sent.'); window.location.href='voter_login.html';</script>";
                } else {
                    echo "Error sending email: " . $mail->ErrorInfo;
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "<script>alert('Email already used.'); window.location.href='voter_register.html';</script>";
        }
    } else {
        echo "<script>alert('Citizenship already used.'); window.location.href='voter_register.html';</script>";
    }
}

// Close the connection
mysqli_close($conn);
