<?php
session_start();

//code by : CQP
$IP = getenv ( "REMOTE_ADDR" );
//code by : CQP

include('includes/config.php');
error_reporting(0);

//code by : minrui
//if google recaptcha is verified, provide secret key
if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
    {
        $secret="6Lea7L0eAAAAABIfndQ3CIqgOSbAMCFvFLJzKwDN";

        $response=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $data=json_decode($response);
    }

if(isset($_POST['signup']))
{
//code for captcha verification
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
//Code for student ID
$count_my_page = ("studentid.txt");
$hits = file($count_my_page);
$hits[0] ++;
$fp = fopen($count_my_page , "w");
fputs($fp , "$hits[0]");
fclose($fp);

// htmlspecialchars is implemented to prevent XSS attacks
$StudentId = htmlspecialchars($hits[0]);
$fname = htmlspecialchars($_POST['fullanme']);
$mobileno = htmlspecialchars($_POST['mobileno']);
$email = htmlspecialchars($_POST['email']);

//code by : CQP & Ryan
// Password hash uses sha1 encryption method, not readable in db
$password=sha1($_POST['password']);

$status=1;
$sql="INSERT INTO  tblstudents(StudentId,FullName,MobileNumber,EmailId,Password,Status) VALUES(:StudentId,:fname,:mobileno,:email,:password,:status)";
$query = $dbh->prepare($sql);
$query->bindParam(':StudentId',$StudentId,PDO::PARAM_STR);
$query->bindParam(':fname',$fname,PDO::PARAM_STR);
$query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':password',$password,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
echo '<script>alert("Your Registration successfull and your student id is  "+"'.$StudentId.'")</script>';
}
else
{
echo "<script>alert('Something went wrong. Please try again');</script>";
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
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>Online Library Management System | Student Signup</title>
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
<script type="text/javascript">
function valid()
{
if(document.signup.password.value!= document.signup.confirmpassword.value)
{
alert("Password and Confirm Password Field do not match  !!");
document.signup.confirmpassword.focus();
return false;
}
return true;
}
</script>
<script>
function checkAvailability() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_availability.php",
data:'emailid='+$("#emailid").val(),
type: "POST",
success:function(data){
$("#user-availability-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>

<!-- Code By : CQP -->
<!-- Password Strength Checker -->
<script>
function PasscheckAvailability() {
$("#loaderIcon").show();
jQuery.ajax({
url: "pass_checker.php",
data:'passID='+$("#passID").val(),
type: "POST",
success:function(data){
$("#pass-availability-status").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>

<!-- Password Matching Checker -->
<script>
function PassMatch() {
$("#loaderIcon").show();
jQuery.ajax({
url: "pass_match.php",
data:'passMatchID='+$("#passMatchID").val(),
type: "POST",
success:function(data){
$("#pass-match").html(data);
$("#loaderIcon").hide();
},
error:function (){}
});
}
</script>
<!-- Code By : CQP -->

</head>
<body>
    <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">User Signup</h4>

                            </div>

        </div>
             <div class="row">

<div class="col-md-9 col-md-offset-1">
               <div class="panel panel-danger">
                        <div class="panel-heading">
                           SINGUP FORM
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="post" onSubmit="return valid();">
<div class="form-group">
<label>Enter Full Name</label>
<input class="form-control" type="text" name="fullanme" autocomplete="off" required />
</div>


<div class="form-group">
<label>Mobile Number :</label>
<input class="form-control" type="text" name="mobileno" maxlength="10" autocomplete="off" required />
</div>

<div class="form-group">
<label>Enter Email</label>
<input class="form-control" type="email" name="email" id="emailid" onBlur="checkAvailability()"  autocomplete="off" required  />
   <span id="user-availability-status" style="font-size:12px;"></span>
</div>

<div class="form-group">
<label>Enter Password</label>
<input class="form-control" type="password" name="password" id="passID" autocomplete="off" required onBlur="PasscheckAvailability()" />
   <span id="pass-availability-status" style="font-size:12px;"></span>
</div>

<div class="form-group">
<label>Confirm Password </label>
<input class="form-control"  type="password" name="confirmpassword" id="passMatchID" autocomplete="off" required onBlur="PassMatch()" />
   <span id="pass-match" style="font-size:12px;"></span>
</div>
 <div class="form-group">
<label>Verification code : </label>
<input type="text"  name="vercode" maxlength="5" autocomplete="off" required style="width: 150px; height: 25px;" />&nbsp;<img src="captcha.php">
</div>
                                    
<!--code by : minrui-->
<!-- google recaptcha v2 - i am not robot box-->
<div class="g-recaptcha" data-sitekey="6Lea7L0eAAAAABa7GltQ1MS_e5MNouB-jv79u_KP"></div>                                   
                                    
<button type="submit" name="signup" class="btn btn-danger" id="submit">Register Now </button>

                                    </form>
                            </div>
                        </div>
                            </div>
        </div>
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
