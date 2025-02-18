<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checking email address</title>
  <script src="../js/getRegion_ajax.js" defer></script>

</head>
<body>
<?php 
require "../home/email_send.php";
$email = "nasdasasdasdasdasdasd@gmail.com";
$name='neupaneji';

if(sendMail($email, $name, "Voter registration successful", "Wait for the ID and account verification")){
  echo "Email sent successfully to $name";
}
else{
    echo "Error occurred while sending email to $name";
}
?>
</body>
</html>