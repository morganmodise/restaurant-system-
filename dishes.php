
<?php
session_start();
include("connection/connect.php");

$res_id = isset($_GET['res_id']) ? intval($_GET['res_id']) : 0;
if($res_id<=0){ die("Restaurant not found"); }

if(!isset($_SESSION["cart"])){
    $_SESSION["cart"]=[];
}

/* ADD ITEM */
if(isset($_GET["action"]) && $_GET["action"]=="add"){
    $id=intval($_GET["id"]);
    $qty=intval($_POST["quantity"]);

    $stmt=$db->prepare("SELECT * FROM dishes WHERE d_id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result=$stmt->get_result();
    $dish=$result->fetch_assoc();

    if($dish){
        if(isset($_SESSION["cart"][$id])){
            $_SESSION["cart"][$id]["qty"]+=$qty;
        }else{
            $_SESSION["cart"][$id]=[
                "id"=>$dish["d_id"],
                "title"=>$dish["title"],
                "price"=>$dish["price"],
                "qty"=>$qty
            ];
        }
    }
}

/* REMOVE ITEM */
if(isset($_GET["action"]) && $_GET["action"]=="remove"){
    $id=intval($_GET["id"]);
    unset($_SESSION["cart"][$id]);
}

/* CLEAR CART */
if(isset($_GET["action"]) && $_GET["action"]=="clear"){
    unset($_SESSION["cart"]);
}

$stmt=$db->prepare("SELECT * FROM restaurant WHERE rs_id=?");
$stmt->bind_param("i",$res_id);
$stmt->execute();
$res=$stmt->get_result();
$restaurant=$res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dishes</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f6f9;
}

.food-card{
border-radius:14px;
box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

.cart-box{
position:sticky;
top:20px;
}

.price{
font-weight:bold;
color:#28a745;
font-size:18px;
}

</style>

</head>

<body>

<nav class="navbar navbar-dark bg-dark">
<div class="container">

<a class="navbar-brand fw-bold" href="index.php">
Food Ordering
</a>

</div>
</nav>

<div class="container mt-4">

<div class="row g-4">

<!-- MENU -->
<div class="col-lg-8">

<h3 class="mb-3"><?php echo $restaurant["title"]; ?></h3>
<p class="text-muted"><?php echo $restaurant["address"]; ?></p>

<?php
$stmt=$db->prepare("SELECT * FROM dishes WHERE rs_id=?");
$stmt->bind_param("i",$res_id);
$stmt->execute();
$dishes=$stmt->get_result();

while($dish=$dishes->fetch_assoc()){
?>

<div class="card food-card mb-3">
<div class="row g-0">

<div class="col-4">
<img src="admin/Res_img/dishes/<?php echo $dish['img']; ?>"
class="img-fluid rounded-start">
</div>

<div class="col-8">

<div class="card-body">

<h5><?php echo $dish["title"]; ?></h5>

<p class="text-muted small">
<?php echo $dish["slogan"]; ?>
</p>

<div class="d-flex justify-content-between align-items-center">

<span class="price">R<?php echo $dish["price"]; ?></span>

<form method="post"
action="dishes.php?res_id=<?php echo $res_id;?>&action=add&id=<?php echo $dish['d_id']; ?>">

<input type="number"
name="quantity"
value="1"
min="1"
style="width:60px">

<button class="btn btn-success btn-sm">
Add
</button>

</form>

</div>

</div>

</div>

</div>
</div>

<?php } ?>

</div>

<!-- CART -->
<div class="col-lg-4">

<div class="card cart-box">

<div class="card-header bg-success text-white">
Your Cart
</div>

<div class="card-body">

<?php
$total=0;

if(!empty($_SESSION["cart"])){

foreach($_SESSION["cart"] as $item){

?>

<div class="d-flex justify-content-between mb-2">

<div>
<strong><?php echo $item["title"]; ?></strong>
<br>
<small>Qty: <?php echo $item["qty"]; ?></small>
</div>

<div>

R<?php echo $item["price"]; ?>

<a class="text-danger ms-2"
href="dishes.php?res_id=<?php echo $res_id;?>&action=remove&id=<?php echo $item["id"]; ?>">
✕
</a>

</div>

</div>

<?php
$total+=($item["price"]*$item["qty"]);
}
}else{

echo "<p class='text-muted'>Cart empty</p>";

}
?>

</div>

<div class="card-footer text-center">

<h5>Total: R<?php echo $total; ?></h5>

<a href="dishes.php?res_id=<?php echo $res_id;?>&action=clear"
class="btn btn-outline-danger btn-sm">
Clear Cart
</a>

<?php if($total>0){ ?>

<a href="checkout.php?res_id=<?php echo $res_id;?>"
class="btn btn-success">
Checkout
</a>

<?php } ?>

</div>

</div>

</div>

</div>

</div>

</body>
</html>
