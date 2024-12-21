<?php
// Database connection details
$servername = "localhost";  // Replace with your database server details
$username = "root";         // Replace with your MySQL username
$pass = "";             // Replace with your MySQL password
$dbname = "online_election"; // Your database name

// Create connection (procedural style)
$conn = mysqli_connect($servername, $username, $pass, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>