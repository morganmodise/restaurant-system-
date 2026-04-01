<?php
// =========================================
// ALL ORDERS DASHBOARD PAGE
// =========================================

// Display all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../connection/connect.php");
session_start();

// Redirect to login if session is not set
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>All Orders - Dashboard</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">

    <!-- CSS Files -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar">

    <!-- Preloader -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="main-wrapper">

        <!-- Header -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header">
                    <a class="navbar-brand" href="dashboard.php">
                        <span><img src="images/icn.png" alt="homepage" class="dark-logo" /></span>
                    </a>
                </div>
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- Left Navbar Items -->
                    </ul>
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown">
                                <img src="images/bookingSystem/user-icn.png" alt="user" class="profile-pic" />
                            </a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Sidebar -->
        <div class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li><a href="dashboard.php"><i class="fa fa-tachometer"></i><span>Dashboard</span></a></li>
                        <li class="nav-label">Log</li>
                        <li><a href="all_users.php"><i class="fa fa-user f-s-20"></i> <span>Users</span></a></li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false">
                                <i class="fa fa-archive f-s-20 color-warning"></i>
                                <span class="hide-menu">Restaurant</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_restaurant.php">All Restaurants</a></li>
                                <li><a href="add_category.php">Add Category</a></li>
                                <li><a href="add_restaurant.php">Add Restaurant</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false">
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                                <span class="hide-menu">Menu</span>
                            </a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="all_menu.php">All Menus</a></li>
                                <li><a href="add_menu.php">Add Menu</a></li>
                            </ul>
                        </li>
                        <li><a href="all_orders.php"><i class="fa fa-shopping-cart"></i><span>Orders</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Page Wrapper -->
        <div class="page-wrapper">

            <div style="padding-top: 10px;">
                <marquee onMouseOver="this.stop()" onMouseOut="this.start()">
                    <a href="https://www.youtube.com/@codecampbdofficial">Code Camp BD</a> is the sole owner of this script. For any problems contact <a href="https://www.facebook.com/dev.mhrony">MH RONY</a>.
                </marquee>
            </div>

            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline-primary">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white">All Orders</h4>
                            </div>
                            <div class="table-responsive m-t-40">
                                <table id="myTable" class="table table-bordered table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>User</th>
                                            <th>Title</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Address</th>
                                            <th>Status</th>
                                            <th>Reg-Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT users.*, users_orders.* FROM users INNER JOIN users_orders ON users.u_id = users_orders.u_id";
                                        $query = mysqli_query($db, $sql);

                                        if(mysqli_num_rows($query) == 0){
                                            echo '<tr><td colspan="8" class="text-center">No Orders Found</td></tr>';
                                        } else {
                                            while($row = mysqli_fetch_assoc($query)){
                                                echo "<tr>";
                                                echo "<td>{$row['username']}</td>";
                                                echo "<td>{$row['title']}</td>";
                                                echo "<td>{$row['quantity']}</td>";
                                                echo "<td>\${$row['price']}</td>";
                                                echo "<td>{$row['address']}</td>";

                                                $status = $row['status'];
                                                if(empty($status) || $status=="NULL"){
                                                    echo '<td><button class="btn btn-info"><span class="fa fa-bars"></span> Dispatch</button></td>';
                                                } elseif($status=="in process"){
                                                    echo '<td><button class="btn btn-warning"><span class="fa fa-cog fa-spin"></span> On The Way!</button></td>';
                                                } elseif($status=="closed"){
                                                    echo '<td><button class="btn btn-primary"><span class="fa fa-check-circle"></span> Delivered</button></td>';
                                                } elseif($status=="rejected"){
                                                    echo '<td><button class="btn btn-danger"><span class="fa fa-close"></span> Cancelled</button></td>';
                                                }

                                                echo "<td>{$row['date']}</td>";
                                                echo "<td>
                                                    <a href='delete_orders.php?order_del={$row['o_id']}' onclick='return confirm(\"Are you sure?\");' class='btn btn-danger btn-xs'><i class='fa fa-trash'></i></a>
                                                    <a href='view_order.php?user_upd={$row['o_id']}' class='btn btn-info btn-sm'><i class='fa fa-edit'></i></a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- Container Fluid -->

            <?php include "include/footer.php"; ?>

        </div> <!-- Page Wrapper -->

    </div> <!-- Main Wrapper -->

    <!-- Scripts -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

</body>
</html>