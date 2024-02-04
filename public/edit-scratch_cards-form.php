
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

    $user_id = $db->escapeString(($_POST['user_id']));
    $amount = $db->escapeString(($_POST['amount']));
    $status = $db->escapeString(($_POST['status']));
    $error = array();

{

$sql_query = "UPDATE scratch_cards SET user_id='$user_id',amount='$amount',status='$status' WHERE id =  $ID";
$db->sql($sql_query);
$update_result = $db->getResult();
if (!empty($update_result)) {
   $update_result = 0;
} else {
   $update_result = 1;
}

        // check update result
        if ($update_result == 1) {
            $error['update_banch'] = " <section class='content-header'><span class='label label-success'>scratch Cards updated Successfully</span></section>";
        } else {
            $error['update_banch'] = " <span class='label label-danger'>Failed update</span>";
        }
    }
}

// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM scratch_cards WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "scratch_cards.php";
    </script>
<?php } ?>
<section class="content-header">
    <h1>
        Edit scratch Cards<small><a href='scratch_cards.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to scratch Cards</a></small></h1>
    <small><?php echo isset($error['update_banch']) ? $error['update_banch'] : ''; ?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form url="update_branches_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                       <div class="row">
                            <div class="form-group">
                                <div class='col-md-10'>
                                    <label for="exampleInputEmail1">User ID</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="user_id" value="<?php echo $res[0]['user_id']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-10'>
                                    <label for="exampleInputEmail1">Amount</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="amount" value="<?php echo $res[0]['amount']; ?>">
                                </div> 
                            </div>
                        </div>
                        <br>
                        <div class="row">
                        <div class="form-group col-md-10">
                                    <label class="control-label">Status</label><i class="text-danger asterik">*</i><br>
                                    <div id="status" class="btn-group">
                                        <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="0" <?= ($res[0]['status'] == 0) ? 'checked' : ''; ?>> Not-verified
                                        </label>
                                        <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="1" <?= ($res[0]['status'] == 1) ? 'checked' : ''; ?>> Verified
                                        </label>
                                        <label class="btn btn-danger" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="2" <?= ($res[0]['status'] == 2) ? 'checked' : ''; ?>> Blocked
                                        </label>
                                    </div>
                                </div>
                        </div>
                        <br>
                    </div>
                  
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>

<?php $db->disconnect(); ?>
<script>
    var changeCheckbox = document.querySelector('#trial_earning_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#trial_earnings').val(1);

        } else {
            $('#trial_earnings').val(0);
        }
    };
</script>