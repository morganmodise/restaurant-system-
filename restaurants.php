
<?php
include("connection/connect.php");
session_start();
error_reporting(E_ALL);
ini_set('display_errors',1);

$search = isset($_GET['search']) ? mysqli_real_escape_string($db,$_GET['search']) : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Search Restaurants</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
background:#f6f7fb;
font-family:Segoe UI;
}

.navbar{
background:#ff6600;
}

.card{
border-radius:14px;
box-shadow:0 4px 15px rgba(0,0,0,0.08);
}

.search-box{
max-width:600px;
margin:auto;
}

.dish-card img{
height:180px;
object-fit:cover;
}

</style>

</head>

<body>

<nav class="navbar navbar-dark">
<div class="container">

<a class="navbar-brand" href="index.php">🍔 FoodExpress</a>

</div>
</nav>


<div class="container mt-5">

<div class="search-box mb-5">

<form method="GET">

<div class="input-group input-group-lg">

<input type="text"
name="search"
value="<?php echo $search;?>"
class="form-control"
placeholder="Search restaurants, dishes, food...">

<button class="btn btn-warning">

<i class="fa fa-search"></i>

</button>

</div>

</form>

</div>


<?php
if($search!=""){
?>

<h4 class="mb-3">Restaurants</h4>

<div class="row g-4">

<?php

$restaurants=mysqli_query($db,"
SELECT * FROM restaurant 
WHERE title LIKE '%$search%'
OR address LIKE '%$search%'
");

if(mysqli_num_rows($restaurants)==0){
echo "<p>No restaurants found.</p>";
}

while($r=mysqli_fetch_array($restaurants)){
?>

<div class="col-md-6">

<div class="card p-3">

<div class="row">

<div class="col-4">

<img src="admin/Res_img/<?php echo $r['image'];?>"
class="img-fluid rounded">

</div>

<div class="col-8">

<h5><?php echo $r['title'];?></h5>

<p class="text-muted small">
<?php echo $r['address'];?>
</p>

<a href="dishes.php?res_id=<?php echo $r['rs_id'];?>"
class="btn btn-success btn-sm">

View Menu

</a>

</div>

</div>

</div>

</div>

<?php } ?>

</div>



<hr class="my-5">


<h4 class="mb-3">Dishes</h4>

<div class="row g-4">

<?php

$dishes=mysqli_query($db,"
SELECT * FROM dishes
WHERE title LIKE '%$search%'
OR slogan LIKE '%$search%'
");

if(mysqli_num_rows($dishes)==0){
echo "<p>No dishes found.</p>";
}

while($d=mysqli_fetch_array($dishes)){
?>

<div class="col-md-4">

<div class="card dish-card">

<img src="admin/Res_img/dishes/<?php echo $d['img'];?>"
class="w-100">

<div class="p-3">

<h6><?php echo $d['title'];?></h6>

<p class="small text-muted">
<?php echo $d['slogan'];?>
</p>

<div class="d-flex justify-content-between">

<strong>$<?php echo $d['price'];?></strong>

<a href="dishes.php?res_id=<?php echo $d['rs_id'];?>"
class="btn btn-warning btn-sm">

Order

</a>

</div>

</div>

</div>

</div>

<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>
