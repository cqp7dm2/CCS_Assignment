<?php
session_start();

//code by : CQP
$IP = getenv ( "REMOTE_ADDR" );
//code by : CQP

// foreach ( explode('/',$_SERVER[]) as $pair ) {
//     list($key,$value) = split('=',$pair,2);
//     $param[$key] = stripslashes($value);
// }
//
// foreach ( explode('/',$_SERVER['PATH_INFO']) as $pair ) {
//     list($key,$value) = split('=',$pair,2);
//     $param[$key] = stripslashes($value);
// }

// htmlspecialchars is implemented to prevent XSS attacks under email ID
// htmlspecialchars is not implemented on password field as it is hashed, all script related
// symbols would be encrypted and have no effects

error_reporting(0);
include('includes/config.php');
if($_SESSION['login']!=''){
$_SESSION['login']='';
}

//code by : minrui
//if google recaptcha is verified, provide secret key
 if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
    {
        $secret="6Lea7L0eAAAAABIfndQ3CIqgOSbAMCFvFLJzKwDN";

        $response=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $data=json_decode($response);
    }

if(isset($_POST['login']))
{
if ($_POST["vercode"] != $_SESSION["vercode"] OR $_SESSION["vercode"]=='')  {
        echo "<script>alert('Incorrect verification code');</script>" ;
    }
        //code by : minrui
        //if google recaptcha empty
        if(empty($_POST['g-recaptcha-response']))
        {
            echo "<script>alert('Please verify reCaptcha');</script>";
        }
        else {
$email = htmlspecialchars($_POST['emailid']);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Incorrect Email');</script>" ;
}
$password = sha1($_POST['password']);
$sql ="SELECT FullName,EmailId,Password,StudentId,Status FROM tblstudents WHERE EmailId=:email and Password=:password";
$query= $dbh -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{
 foreach ($results as $result) {
 $_SESSION['stdid']=$result->StudentId;
 $_SESSION['username']=$result->FullName;
if($result->Status==1)
{
$_SESSION['login']=$_POST['emailid'];
echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
} else {
echo "<script>alert('Your Account Has been blocked .Please contact admin');</script>";

}
}

}

else{
echo "<script>alert('Invalid Details');</script>";
}
}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | </title>
    <!-- code by : minrui -->
    <!-- google recaptcha implementation -->
    <script src="https://www.google.com/recaptcha/api.js"
    async defer></script>
        
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>
<body>
    <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
<div class="content-wrapper">
<div class="container">
<div class="row pad-botm">
<div class="col-md-12">
<h4 class="header-line">USER LOGIN FORM</h4>
</div>
</div>

<!--LOGIN PANEL START-->
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" >
<div class="panel panel-info">
<div class="panel-heading">
 LOGIN FORM
</div>
<div class="panel-body">
<form role="form" method="post">

<div class="form-group">
<label>Enter Email id</label>
<input class="form-control" type="text" name="emailid" required autocomplete="off" />
</div>
<div class="form-group">
<label>Password</label>
<input class="form-control" type="password" name="password" required autocomplete="off"  />
<p class="help-block"><a href="user-forgot-password.php">Forgot Password</a></p>
</div>

 <div class="form-group">
<label>Verification code : </label>
<input type="text" class="form-control1"  name="vercode" maxlength="5" autocomplete="off" required  style="height:25px;" />&nbsp;<img src="captcha.php">
</div>

<!--code by : minrui-->
<!-- google recaptcha v2 - i am not robot box-->
<div class="g-recaptcha" data-sitekey="6Lea7L0eAAAAABa7GltQ1MS_e5MNouB-jv79u_KP"></div>       

 <button type="submit" name="login" class="btn btn-info">LOGIN </button> | <a href="signup.php">Not Register Yet</a>
</form>
 </div>
</div>
</div>
</div>
<!---LOGIN PABNEL END-->


    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>

</body>
</html>
