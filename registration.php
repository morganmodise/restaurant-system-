<?php
session_start();
include("connection/connect.php");

error_reporting(E_ALL);
ini_set('display_errors',1);

$message="";
$success="";

/* ================= REGISTER ================= */

if(isset($_POST['submit'])){

$username  = trim($_POST['username']);
$firstname = trim($_POST['firstname']);
$lastname  = trim($_POST['lastname']);
$email     = trim($_POST['email']);
$phone     = trim($_POST['phone']);
$password  = $_POST['password'];
$cpassword = $_POST['cpassword'];
$address   = trim($_POST['address']);

if(
    $username=="" || $firstname=="" || $lastname=="" ||
    $email=="" || $phone=="" || $password=="" || $address==""
){
    $message="All fields are required!";
}
elseif($password!=$cpassword){
    $message="Passwords do not match!";
}
elseif(strlen($password)<6){
    $message="Password must be at least 6 characters";
}
elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $message="Invalid email address";
}
else{

    /* CHECK USERNAME */
    $stmt=$db->prepare("SELECT u_id FROM users WHERE username=? OR email=?");
    $stmt->bind_param("ss",$username,$email);
    $stmt->execute();
    $result=$stmt->get_result();

    if($result->num_rows>0){
        $message="Username or Email already exists!";
    }else{

        /* HASH PASSWORD (SECURE) */
        $hashed=password_hash($password,PASSWORD_DEFAULT);

        $insert=$db->prepare(
            "INSERT INTO users(username,f_name,l_name,email,phone,password,address)
             VALUES(?,?,?,?,?,?,?)"
        );

        $insert->bind_param(
            "sssssss",
            $username,
            $firstname,
            $lastname,
            $email,
            $phone,
            $hashed,
            $address
        );

        if($insert->execute()){
            $success="Registration successful! Redirecting...";
            header("refresh:2;url=login.php");
        }else{
            $message="Registration failed!";
        }
    }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Register - FoodExpress</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:linear-gradient(135deg,#ff6600,#111);
font-family:Segoe UI;
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
padding:20px;
}

.register-card{
background:#fff;
padding:35px;
border-radius:20px;
width:100%;
max-width:600px;
box-shadow:0 10px 40px rgba(0,0,0,.25);
}

.logo{
font-size:28px;
font-weight:bold;
color:#ff6600;
text-align:center;
margin-bottom:20px;
}

.form-control{
border-radius:12px;
height:46px;
}

textarea.form-control{
height:auto;
}

.btn-register{
background:#ff6600;
border:none;
border-radius:12px;
height:48px;
font-weight:600;
}

.btn-register:hover{
background:#e65c00;
}

.msg{
padding:10px;
border-radius:10px;
margin-bottom:15px;
text-align:center;
}

.error{
background:#ffe5e5;
color:#c40000;
}

.success{
background:#e6ffea;
color:#008c2f;
}

</style>

</head>

<body>

<div class="register-card">

<div class="logo">🍔 FoodExpress</div>

<h5 class="text-center mb-4">Create Account</h5>

<?php if($message!=""){ ?>
<div class="msg error"><?php echo $message;?></div>
<?php } ?>

<?php if($success!=""){ ?>
<div class="msg success"><?php echo $success;?></div>
<?php } ?>

<form method="post">

<div class="row">

<div class="col-md-12 mb-3">
<input type="text" name="username" class="form-control" placeholder="Username" required>
</div>

<div class="col-md-6 mb-3">
<input type="text" name="firstname" class="form-control" placeholder="First Name" required>
</div>

<div class="col-md-6 mb-3">
<input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
</div>

<div class="col-md-6 mb-3">
<input type="email" name="email" class="form-control" placeholder="Email Address" required>
</div>

<div class="col-md-6 mb-3">
<input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
</div>

<div class="col-md-6 mb-3">
<input type="password" name="password" class="form-control" placeholder="Password" required>
</div>

<div class="col-md-6 mb-3">
<input type="password" name="cpassword" class="form-control" placeholder="Confirm Password" required>
</div>

<div class="col-md-12 mb-3">
<textarea name="address" class="form-control" placeholder="Delivery Address" rows="3" required></textarea>
</div>

</div>

<button type="submit" name="submit" class="btn btn-register w-100">
Register Account
</button>

</form>

<div class="text-center mt-3">
Already registered?
<a href="login.php" style="color:#ff6600;font-weight:600;">Login</a>
</div>

</div>

</body>
</html>