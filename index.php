
<?php
include("connection/connect.php");
session_start();
error_reporting(E_ALL);
ini_set('display_errors',1);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Online Food Ordering System</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
font-family:Segoe UI;
background:#f6f7fb;
}

/* NAVBAR */

.navbar{
background:linear-gradient(90deg,#ff7a18,#ff6600);
box-shadow:0 2px 10px rgba(0,0,0,0.15);
}

.navbar-brand{
font-weight:bold;
font-size:22px;
}

/* HERO */

.hero{
background:url(images/img/pimg.jpg) center/cover no-repeat;
padding:150px 0;
color:white;
text-align:center;
position:relative;
}

.hero::after{
content:"";
position:absolute;
top:0;
left:0;
right:0;
bottom:0;
background:rgba(0,0,0,0.55);
}

.hero .content{
position:relative;
z-index:2;
}

.search-box{
max-width:600px;
margin:auto;
margin-top:20px;
}

/* FOOD CARDS */

.food-card{
background:white;
border-radius:14px;
overflow:hidden;
box-shadow:0 5px 18px rgba(0,0,0,0.1);
transition:0.3s;
}

.food-card:hover{
transform:translateY(-6px);
}

.food-card img{
height:200px;
object-fit:cover;
}

.food-card-body{
padding:18px;
}

.price{
color:#ff6600;
font-weight:bold;
font-size:18px;
}

/* RESTAURANTS */

.restaurant{
background:white;
border-radius:12px;
padding:15px;
box-shadow:0 4px 14px rgba(0,0,0,0.08);
transition:.3s;
}

.restaurant:hover{
transform:scale(1.02);
}

.restaurant img{
border-radius:10px;
}

/* SECTION TITLE */

.section-title{
font-weight:700;
margin-bottom:30px;
}

/* FOOTER */

footer{
background:#111;
color:white;
padding:40px;
margin-top:60px;
text-align:center;
}

</style>

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-dark">
<div class="container">

<a class="navbar-brand" href="index.php">
🍔 FoodExpress
</a>

<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navMenu">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="index.php">Home</a>
</li>

<li class="nav-item">
<a class="nav-link" href="restaurants.php">Restaurants</a>
</li>

<?php
if(empty($_SESSION["user_id"]))
{
echo '
<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
<li class="nav-item"><a class="nav-link" href="registration.php">Register</a></li>
';
}
else
{
echo '
<li class="nav-item"><a class="nav-link" href="your_orders.php">My Orders</a></li>
<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
';
}
?>

</ul>

</div>
</div>
</nav>



<!-- HERO -->

<section class="hero">

<div class="container content">

<h1 class="display-5 fw-bold">
Order Delivery & Take-Out
</h1>

<p class="lead">
Fastest way to order food online
</p>

<div class="search-box">

<form method="GET" action="restaurants.php">

<div class="input-group input-group-lg">

<input type="text"
name="search"
class="form-control"
placeholder="Search restaurants or dishes...">

<button class="btn btn-warning">
<i class="fa fa-search"></i>
</button>

</div>

</form>

</div>

</div>

</section>



<!-- POPULAR DISHES -->

<section class="container mt-5">

<h2 class="text-center section-title">
🔥 Popular Dishes
</h2>

<div class="row g-4">

<?php

$query=mysqli_query($db,"SELECT * FROM dishes ORDER BY rand() LIMIT 6");

while($r=mysqli_fetch_array($query))
{

?>

<div class="col-md-4">

<div class="food-card">

<img src="admin/Res_img/dishes/<?php echo $r['img']; ?>" class="w-100">

<div class="food-card-body">

<h5><?php echo $r['title']; ?></h5>

<p class="text-muted small">
<?php echo $r['slogan']; ?>
</p>

<div class="d-flex justify-content-between align-items-center">

<span class="price">R<?php echo $r['price']; ?></span>

<a href="dishes.php?res_id=<?php echo $r['rs_id']; ?>"
class="btn btn-warning btn-sm">

Order

</a>

</div>

</div>

</div>

</div>

<?php } ?>

</div>

</section>



<!-- RESTAURANTS -->

<section class="container mt-5">

<h2 class="text-center section-title">
🍽 Featured Restaurants
</h2>

<div class="row g-4">

<?php

$ress=mysqli_query($db,"SELECT * FROM restaurant ORDER BY rand() LIMIT 6");

while($rows=mysqli_fetch_array($ress))
{

?>

<div class="col-md-6">

<div class="restaurant">

<div class="row">

<div class="col-4">

<img src="admin/Res_img/<?php echo $rows['image']; ?>"
width="100%" height="100">

</div>

<div class="col-8">

<h5><?php echo $rows['title']; ?></h5>

<p class="small text-muted">
<?php echo $rows['address']; ?>
</p>

<div class="mb-2 text-warning">

<i class="fa fa-star"></i>
<i class="fa fa-star"></i>
<i class="fa fa-star"></i>
<i class="fa fa-star"></i>
<i class="fa fa-star-half"></i>

</div>

<a href="dishes.php?res_id=<?php echo $rows['rs_id']; ?>"
class="btn btn-success btn-sm">

View Menu

</a>

</div>

</div>

</div>

</div>

<?php } ?>

</div>

</section>



<!-- FOOTER -->

<footer>

<p>
© <?php echo date("Y"); ?> FoodExpress
</p>

<p class="small text-muted">
Fastest food delivery platform
</p>

</footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
