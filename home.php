<?php session_start();

include_once('includes/custom-functions.php');
include_once('includes/functions.php');
$function = new custom_functions;

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;
// if session not set go to login page
if (!isset($_SESSION['username'])) {
    header("location:index.php");
}
// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}
// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;
$function = new custom_functions;
include "header.php";
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Color Challenge - Dashboard</title>
</head>

<body>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Home</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="home.php"> <i class="fa fa-home"></i> Home</a>
                </li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php
                            $sql = "SELECT * FROM users WHERE status=1";
                            $db->sql($sql);
                            $res = $db->getResult();
                            $num = $db->numRows($res);
                            echo $num;
                             ?></h3>
                            <p>Users</p>
                        </div>
                        <div class="icon"><i class="fa fa-users"></i></div>
                        <a href="users.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php
                            $num = 'Green';
                            echo $num;
                             ?></h3>
                            <p>Expect Result</p>
                        </div>
                       
                        <a href="analysis.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php
                            $num = 'Rs. 25,000';
                            echo $num;
                             ?></h3>
                            <p>Unpaid Withdrawals</p>
                        </div>
                       
                        <a href="withdrawals.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php
                            $num = '25';
                            echo $num;
                             ?></h3>
                            <p>Earned Users</p>
                        </div>
                       
                        <a href="analysis.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>