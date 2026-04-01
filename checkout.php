
<?php
include("connection/connect.php");

/* SAFE SESSION START */
if(session_status() === PHP_SESSION_NONE){
session_start();
}

error_reporting(E_ALL);
ini_set('display_errors',1);

/* LOGIN CHECK */
if(empty($_SESSION["user_id"])){
header("location:login.php");
exit();
}

/* TOTAL CALCULATION */
$total = 0;

if(!empty($_SESSION["cart"])){

foreach($_SESSION["cart"] as $item){

$total += ($item["price"] * $item["qty"]);

}

}

/* PLACE ORDER */
if(isset($_POST['submit']) && !empty($_SESSION["cart"])){

foreach($_SESSION["cart"] as $item){

$sql="INSERT INTO users_orders (u_id,title,quantity,price)
VALUES(
'".$_SESSION["user_id"]."',
'".$item["title"]."',
'".$item["qty"]."',
'".$item["price"]."'
)";

mysqli_query($db,$sql);

}

/* CLEAR CART */
unset($_SESSION["cart"]);

echo "<script>alert('Your order has been placed successfully!');</script>";
echo "<script>window.location='your_orders.php';</script>";

exit();

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Checkout</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>

body{
background:#f5f6fa;
font-family:Segoe UI;
}

.navbar{
background:#ff6600;
}

.checkout-card{
border-radius:16px;
box-shadow:0 6px 20px rgba(0,0,0,0.1);
}

.cart-item{
border-bottom:1px solid #eee;
padding:12px 0;
}

.total-box{
font-size:20px;
font-weight:bold;
color:#ff6600;
}

</style>

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-dark">
<div class="container">

<a class="navbar-brand" href="index.php">
🍔 FoodExpress
</a>

</div>
</nav>


<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="card checkout-card">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">🧾 Order Summary</h5>

</div>


<div class="card-body">

<?php

if(!empty($_SESSION["cart"])){

foreach($_SESSION["cart"] as $item){

?>

<div class="cart-item d-flex justify-content-between">

<div>

<strong><?php echo $item["title"]; ?></strong>

<br>

<small>Quantity: <?php echo $item["qty"]; ?></small>

</div>

<div>

R<?php echo $item["price"]; ?>

</div>

</div>

<?php
}
}
else{

echo "<p class='text-center text-muted'>Your cart is empty</p>";

}
?>

</div>


<div class="card-footer">

<div class="d-flex justify-content-between mb-2">

<span>Delivery</span>

<span class="text-success">Free</span>

</div>

<div class="d-flex justify-content-between total-box">

<span>Total</span>

<span>R<?php echo $total; ?></span>

</div>

<hr>


<form method="post">

<h6 class="mb-3">Payment Method</h6>

<div class="form-check mb-2">

<input class="form-check-input" type="radio" checked>

<label class="form-check-label">
Cash on Delivery
</label>

</div>

<div class="form-check mb-3">

<input class="form-check-input" type="radio" disabled>

<label class="form-check-label">
PayPal (Coming Soon)
</label>

</div>

<button
name="submit"
onclick="return confirm('Confirm your order?')"
class="btn btn-success w-100">

<i class="fa fa-check"></i> Place Order

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>

