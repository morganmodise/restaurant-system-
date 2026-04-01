
<?php
include("connection/connect.php");

if(session_status() === PHP_SESSION_NONE){
session_start();
}

error_reporting(E_ALL);
ini_set('display_errors',1);

if(empty($_SESSION['user_id'])){
header('location:login.php');
exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>My Orders</title>

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

.order-card{
border-radius:16px;
box-shadow:0 6px 18px rgba(0,0,0,0.08);
margin-bottom:20px;
}

.order-header{
background:#111;
color:white;
border-radius:16px 16px 0 0;
padding:12px 18px;
font-weight:600;
}

.order-body{
padding:18px;
}

.price{
font-weight:bold;
color:#ff6600;
font-size:18px;
}

.badge{
font-size:14px;
padding:6px 10px;
}

.cancel-btn{
font-size:14px;
}

.empty-box{
text-align:center;
padding:60px 20px;
color:#888;
}

</style>

</head>

<body>

<nav class="navbar navbar-dark">
<div class="container">

<a class="navbar-brand" href="index.php">
🍔 FoodExpress
</a>

<div>

<a href="index.php" class="btn btn-light btn-sm me-2">
Home
</a>

<a href="restaurants.php" class="btn btn-light btn-sm me-2">
Restaurants
</a>

<a href="logout.php" class="btn btn-dark btn-sm">
Logout
</a>

</div>

</div>
</nav>

<div class="container mt-5">

<h3 class="mb-4">
<i class="fa fa-receipt"></i> My Orders
</h3>

<?php

$query=mysqli_query($db,"SELECT * FROM users_orders WHERE u_id='".$_SESSION['user_id']."' ORDER BY o_id DESC");

if(mysqli_num_rows($query)==0){

echo '

<div class="empty-box">

<i class="fa fa-shopping-cart fa-3x mb-3"></i>

<h5>You have not placed any orders yet</h5>

<a href="restaurants.php" class="btn btn-warning mt-3">
Browse Restaurants
</a>

</div>

';

}
else{

while($row=mysqli_fetch_array($query)){

$status=$row['status'];

if($status=="" || $status=="NULL"){
$statusBadge='<span class="badge bg-info">Dispatch</span>';
}
elseif($status=="in process"){
$statusBadge='<span class="badge bg-warning text-dark">On The Way</span>';
}
elseif($status=="closed"){
$statusBadge='<span class="badge bg-success">Delivered</span>';
}
elseif($status=="rejected"){
$statusBadge='<span class="badge bg-danger">Cancelled</span>';
}

?>

<div class="order-card">

<div class="order-header">

Order #<?php echo $row['o_id']; ?>

</div>

<div class="order-body">

<div class="row">

<div class="col-md-6">

<h5><?php echo $row['title']; ?></h5>

<p class="mb-1">
Quantity: <strong><?php echo $row['quantity']; ?></strong>
</p>

<p class="mb-1">
Date: <?php echo $row['date']; ?>
</p>

</div>

<div class="col-md-3">

<p>Status</p>

<?php echo $statusBadge; ?>

</div>

<div class="col-md-3 text-md-end mt-3 mt-md-0">

<p class="price">
R<?php echo $row['price']; ?>
</p>

<a
href="delete_orders.php?order_del=<?php echo $row['o_id'];?>"
onclick="return confirm('Cancel this order?');"
class="btn btn-danger btn-sm cancel-btn">

<i class="fa fa-trash"></i> Cancel

</a>

</div>

</div>

</div>

</div>

<?php
}
}
?>

</div>

</body>
</html>
