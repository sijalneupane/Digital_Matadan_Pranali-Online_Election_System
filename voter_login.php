<?php
session_start(); // Start the session to store session variables

require 'dbconnection.php';
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $voterId = $_POST['voterId'];
    $password = $_POST['password'];

    // Query to fetch user details based on email and voter_id
    $sql = "SELECT * FROM voters WHERE email = '$email' AND id = '$voterId' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    // Check if user exists
    if (mysqli_num_rows($result) > 0) {
        // Fetch the user's data
        $row = mysqli_fetch_assoc($result);

        // Check if the password matches (assuming passwords are not hashed)
        if ($password === $row['password']) {  // No need for password_verify here since we aren't hashing

            // Set session variables for successful login
            $_SESSION['email'] = $row['email'];
            $_SESSION['election_region'] = $row['election_region'];
            $_SESSION['voterId'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['district']=$row['district'];
            $_SESSION['local_address']=$row['local_address'];
            $_SESSION['contactNo']=$row['contact'];
            $_SESSION['citizenshipNumber']=$row['citizenship_number'];
            $_SESSION['birthDate']=$row['date_of_birth'];

            // Redirect to the dashboard or homepage after successful login
            echo "<script>window.location.href='home.php'</script>"; // Replace with the page you want to redirect to
        
        } else {
            // Invalid password
            echo "<script>alert('Incorrect password'); window.location.href='voter_login.html';</script>";
        }
    } else {
        // No user found with that email and voter ID
        echo "<script>alert('Incorrect email or voter ID.'); window.location.href='voter_login.html'</script>";
    }
}

// Close the connection
mysqli_close($conn);
?>