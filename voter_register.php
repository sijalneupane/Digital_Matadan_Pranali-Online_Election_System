<?php
require 'dbconnection.php';
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from form
    $name = $_POST['name'];
    $contact = $_POST['contactNo'];
    $email = $_POST['email'];
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password
    $password = $_POST['password'];
    $district = $_POST['district'];
    $election_region = $_POST['regionNo'];
    $local_address = $_POST['local_address'];
    $citizenship_number = $_POST['citizenship_number'];
    // Get date of birth in MM-DD-YY format and convert to YYYY-MM-DD format using strtotime
    $date_of_birth = $_POST['date_of_birth'];
    $dob_converted = date('Y-m-d', strtotime($date_of_birth));
    
    
    $sameEmail=0;
    $sameCitizenshipNo=0;
    $sql1 = "SELECT email,citizenship_Number FROM voters";
    $result1 = mysqli_query($conn, $sql1);

    if (mysqli_num_rows($result1) > 0) {
    while($row = mysqli_fetch_assoc($result1)) {
        if ($email==$row['email']) {
        $sameEmail=1;
        }if ($citizenship_number==$row['citizenship_Number']) {
            $sameCitizenshipNo=1;
        }
    }
    }
    if($sameCitizenshipNo==0){
        if ($sameEmail==0) {
        // SQL query to insert data using prepared statements
        $sql = "INSERT INTO voters (name, email, password, district, election_region, local_address, contact, date_of_birth, citizenship_number) 
            VALUES ('$name', '$email', '$password', '$district', '$election_region', '$local_address', '$contact', '$dob_converted', '$citizenship_number')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>window.location.href='voter_login.html';</script>";
        } else {
        echo "Error: " . mysqli_error($conn);
        }
        }else{
            echo "<script>alert('Email already used. . .'); window.location.href='voter_register.html';</script>";
        }
    }else{
        echo "<script>alert('Citizenship already used. .'); window.location.href='voter_register.html';</script>";
    }
}
// Close the connection
mysqli_close($conn);
?>
