<?php
require_once("includes/config.php");

$password=($_POST['passID']);

// Validate password strength
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

// code user email availablity
	if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    echo "<span style='color:red'> Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character .</span>";
    echo "<script>$('#submit').prop('disabled',true);</script>";
	}
	else {
    echo "<span style='color:green'> Strong and Secure Password .</span>";
    echo "<script>$('#submit').prop('disabled',false);</script>";
}


?>
