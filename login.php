<?php
session_start();
include("connection/connect.php");

error_reporting(E_ALL);
ini_set('display_errors',1);

$message = "";

/* ================= LOGIN ================= */
if(isset($_POST['submit'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if($username=="" || $password==""){
        $message="Please enter username and password";
    }else{

        // Secure Prepared Statement
        $stmt = $db->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if($user){

            /* SUPPORT OLD MD5 PASSWORDS + NEW HASH */
            if(
                password_verify($password,$user['password']) ||
                md5($password)==$user['password']
            ){

                $_SESSION['user_id']=$user['u_id'];

                header("Location:index.php");
                exit;

            }else{
                $message="Invalid Password!";
            }

        }else{
            $message="User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>FoodExpress Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>

body{
background:linear-gradient(135deg,#ff6600,#111);
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
font-family:Segoe UI;
}

/* LOGIN CARD */

.login-card{
width:100%;
max-width:420px;
background:#fff;
border-radius:20px;
padding:35px;
box-shadow:0 10px 40px rgba(0,0,0,.25);
animation:fadeIn .6s ease;
}

@keyframes fadeIn{
from{opacity:0;transform:translateY(20px);}
to{opacity:1;transform:translateY(0);}
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
height:48px;
}

.btn-login{
background:#ff6600;
border:none;
border-radius:12px;
height:48px;
font-weight:600;
}

.btn-login:hover{
background:#e65c00;
}

.extra-links{
text-align:center;
margin-top:15px;
}

.extra-links a{
color:#ff6600;
text-decoration:none;
font-weight:600;
}

.error{
background:#ffe5e5;
color:#c40000;
padding:10px;
border-radius:10px;
margin-bottom:15px;
text-align:center;
}

</style>

</head>

<body>

<div class="login-card">

<div class="logo">
🍔 FoodExpress
</div>

<h5 class="text-center mb-4">
Login to your account
</h5>

<?php if($message!=""){ ?>
<div class="error"><?php echo $message;?></div>
<?php } ?>

<form method="post">

<div class="mb-3">
<input type="text"
name="username"
class="form-control"
placeholder="Username"
required>
</div>

<div class="mb-3">
<input type="password"
name="password"
class="form-control"
placeholder="Password"
required>
</div>

<button type="submit" name="submit" class="btn btn-login w-100">
<i class="fa fa-sign-in-alt"></i> Login
</button>

</form>

<div class="extra-links">
Not registered?
<a href="registration.php">Create Account</a>
</div>

</div>

</body>
</html>