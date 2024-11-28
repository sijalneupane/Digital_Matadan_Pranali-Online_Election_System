<?php
session_start(); // Start the session to store session variables

require '../dbconnection.php';
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $voterId = $_POST['voterId'];
    $password = $_POST['password'];

    // Query to fetch user details based on email and voter_id
    $sql = "SELECT * FROM pendingstatus WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    // Check if user exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the user's data
        $row = mysqli_fetch_assoc($result);

        // Check if the password matches (assuming passwords are not hashed)
        if ($password === $row['password']) {  // No need for password_verify here since we aren't hashing
            $_SESSION['error_message'] = 'Your account is on pending. Wait until verification ! ! !We will send email once the verification is done';
            header('Location: voter_login_form.php');
        } else {
            $_SESSION['error_message'] = 'Incorrect password for pending account';
            header('Location: voter_login_form.php');
        }
    } else {
        // No user found with that email and voter ID in pending
        $sql2 = "SELECT * FROM voters WHERE email = '$email' AND id = '$voterId' LIMIT 1";
        $result2 = mysqli_query($conn, $sql2);

        // Check if user exists
        if (mysqli_num_rows($result2) > 0) {
            // Fetch the user's data
            $row2 = mysqli_fetch_assoc($result2);
            // Check if the password matches (assuming passwords are not hashed)
            if ($password === $row2['password']) {  // No need for password_verify here since we aren't hashing
                $joinSql = "SELECT * 
                            FROM voters V 
                            INNER JOIN localaddress la ON V.addressId = la.lid
                            INNER JOIN district D ON D.dId = la.dId
                            WHERE V.email='$email'";
                $result2 = mysqli_query($conn, $joinSql);
                if (mysqli_num_rows($result2) > 0) {
                    $row1 = mysqli_fetch_assoc($result2);
                    // Set session variables for successful login
                    $_SESSION['email'] = $row1['email'];
                    $_SESSION['election_region'] = $row1['regionNo'];
                    $_SESSION['voterId'] = $row1['id'];
                    $_SESSION['name'] = $row1['name'];
                    $_SESSION['district'] = $row1['district'];
                    $_SESSION['local_address'] = $row1['local_address'];
                    $_SESSION['citizenshipNumber'] = $row1['citizenshipNumber'];
                    $_SESSION['birthDate'] = $row1['dateOfBirth'];
                    $_SESSION['gender'] = $row1['gender'];
                    $_SESSION['userPhoto'] = $row1['userPhoto'];
                    $_SESSION['citizenshipFrontPhoto'] = $row1['citizenshipFrontPhoto'];
                    $_SESSION['citizenshipBackPhoto'] = $row1['citizenshipBackPhoto'];

                    header('Location: ../home.php');         // Redirect to the dashboard or homepage after successful login
                } else {
                    $_SESSION['error_message'] = 'Unexpected error occured, please try again';
                    header('Location: voter_login_form.php');
                }
            } else {
                // No user found with that email and voter ID
                $_SESSION['error_message'] = 'Incorrect password for registered account';
                header('Location: voter_login_form.php');
            }
        } else {
            // No user found with that email and voter ID
            $_SESSION['error_message'] = 'Incorrect details';
            header('Location: voter_login_form.php');
        }
    }
}

// Close the connection
mysqli_close($conn);
