<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php

if (isset($_GET['id'])) {
    $ID = $db->escapeString($_GET['id']);
} else {
    // $ID = "";
    return false;
    exit(0);
}
if (isset($_POST['btnEdit'])) {
        
            $status = $db->escapeString(($_POST['status']));
            $delivery_date = $db->escapeString(($_POST['delivery_date']));
            $error = array();

     if (!empty($delivery_date)) 
		{

        $sql_query = "UPDATE orders SET status = '$status',delivery_date = '$delivery_date'  WHERE id =  $ID";
        $db->sql($sql_query);
        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        // check update result
        if ($update_result == 1) {
            $error['update_bank_details'] = " <section class='content-header'><span class='label label-success'>Orders updated Successfully</span></section>";
        } else {
            $error['update_bank_details'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM orders WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();

$user_id = $res[0]['user_id'];

$sql_query_user = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql_query_user);
$result = $db->getResult();

$product_id = $res[0]['product_id'];
$sql_query = "SELECT name FROM product WHERE id = $product_id";
$db->sql($sql_query);
$productResult = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "orders.php";
    </script>
<?php } ?>
<section class="content-header">
    <h1>
        Edit Orders<small><a href='orders.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Orders</a></small></h1>
    <small><?php echo isset($error['update_bank_details']) ? $error['update_bank_details'] : ''; ?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <!-- Main row -->

    <div class="row">
        <div class="col-md-10">

            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <!-- /.box-header -->
                <!-- form start -->
                <form id="edit_bank_details_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name']; ?>" readonly>
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Mobile</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="mobile" value="<?php echo $result[0]['mobile']; ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                            <div class='col-md-3'>
                                  <label for="exampleInputEmail1">Product name</label> <i class="text-danger asterisk">*</i>
                                  <input type="text" class="form-control" name="name" value="<?php echo $productResult[0]['name']; ?>" readonly>  
                                </div>
                                <div class='col-md-3'>
                                <label for="exampleInputEmail1">status</label> <i class="text-danger asterik">*</i>
                                    <select id='status' name="status" class='form-control'>
                                    <option value=''>--Select--</option>
                                    <option value='0' <?php if ($res[0]['status'] == '0') echo 'selected'; ?>>pending</option>
                                      <option value='1' <?php if ($res[0]['status'] == '1') echo 'selected'; ?>>confirmed</option>
                                      <option value='2' <?php if ($res[0]['status'] == '2') echo 'selected'; ?>>delivered</option>
                                      <option value='3' <?php if ($res[0]['status'] == '3') echo 'selected'; ?>>cancelled</option>
                                    </select>
                                </div>
                                <div class='col-md-3'>
                                  <label for="exampleInputEmail1">Delivery Date</label> <i class="text-danger asterisk">*</i>
                                  <input type="date" class="form-control" name="delivery_date" value="<?php echo $res[0]['delivery_date']; ?>">  
                                </div>
                             </div>
                         </div>
                    </div><!-- /.box-body -->
                   
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>

                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>