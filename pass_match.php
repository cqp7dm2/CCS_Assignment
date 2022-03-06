<?php
// require_once("includes/config.php");
//
// $password=($_POST['passMatchID']);
// $Oripassword=($_POST['passID']);
//
// if ($_POST["passID"] != $_POST["passMatchID"])
// {
//   echo "<span style='color:red'> Password UnMatch. Please Try Again .</span>";
//   echo "<script>$('#submit').prop('disabled',true);</script>";
// }else{
//   echo "<span style='color:green'> Password Match .</span>";
//   echo "<script>$('#submit').prop('disabled',false);</script>";
// }

require_once("includes/config.php");

$password=($_POST['passMatchID']);

// Validate password strength
$uppercase = preg_match('@[A-Z]@', $password);
$lowercase = preg_match('@[a-z]@', $password);
$number    = preg_match('@[0-9]@', $password);
$specialChars = preg_match('@[^\w]@', $password);

// check user password strength
	if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    echo "<span style='color:red'> Password UnMatch. Please Try Again .</span>";
    echo "<script>$('#submit').prop('disabled',true);</script>";
	}
	else {
    echo "<span style='color:green'> Password Match .</span>";
    echo "<script>$('#submit').prop('disabled',false);</script>";
}


?>
