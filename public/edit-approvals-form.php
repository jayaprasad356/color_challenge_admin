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

    $mobile = $db->escapeString(($_POST['mobile']));
    $referred_by = $db->escapeString($_POST['referred_by']);
    $refer_bonus= $db->escapeString($_POST['refer_bonus']);
    $basic = $db->escapeString(($_POST['basic']));
    $lifetime = $db->escapeString(($_POST['lifetime']));
    $premium = $db->escapeString(($_POST['premium']));
    $basic_joined_date = $db->escapeString(($_POST['basic_joined_date']));
    $lifetime_joined_date = $db->escapeString(($_POST['lifetime_joined_date']));
    $premium_joined_date = $db->escapeString(($_POST['premium_joined_date']));
    $status = $db->escapeString(($_POST['status']));
	$error = array();

{

$sql_query = "UPDATE approvals SET mobile='$mobile',referred_by='$referred_by',refer_bonus='$refer_bonus',basic = '$basic', lifetime = '$lifetime',premium = '$premium',basic_joined_date = '$basic_joined_date',lifetime_joined_date = '$lifetime_joined_date',premium_joined_date = '$premium_joined_date',status = '$status' WHERE id = $ID";
$db->sql($sql_query);
$update_result = $db->getResult();
if (!empty($update_result)) {
   $update_result = 0;
} else {
   $update_result = 1;
}

// check update result
if ($update_result == 1) {
   $error['update_jobs'] = " <section class='content-header'><span class='label label-success'>Approvals updated Successfully</span></section>";
} else {
   $error['update_jobs'] = " <span class='label label-danger'>Failed to Update</span>";
}
}
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM approvals WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();


if (isset($_POST['btnCancel'])) { ?>
<script>
window.location.href = "approvals.php";
</script>
<?php } ?>

<section class="content-header">
	<h1>
		Edit Approvals<small><a href='approvals.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Approvals</a></small></h1>
	<small><?php echo isset($error['update_jobs']) ? $error['update_jobs'] : ''; ?></small>
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
				</div><!-- /.box-header -->
				<!-- form start -->
				<form name="add_slide_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                            <div class="row">
                                <div class="form-group">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1"> Mobile Number</label> <i class="text-danger asterik">*</i<?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>>
                                     <input type="text" class="form-control" name="mobile" value="<?php echo $res[0]['mobile']; ?>">
                                  </div>
                               <div class="col-md-4">
                                    <label for="exampleInputEmail1"> Refered By</label> <i class="text-danger asterik">*</i<?php echo isset($error['referred_by']) ? $error['referred_by'] : ''; ?>>
                                    <input type="text" class="form-control" name="referred_by" value="<?php echo $res[0]['referred_by']; ?>">
                                 </div> 
                                 <div class="col-md-4">
                                    <label for="exampleInputEmail1"> Refer Bonus</label> <i class="text-danger asterik">*</i<?php echo isset($error['refer_bonus']) ? $error['refer_bonus'] : ''; ?>>
                                    <input type="text" class="form-control" name="refer_bonus" value="<?php echo $res[0]['refer_bonus']; ?>">
                                 </div>   
                            </div>
                         </div>
                         <br>
                         <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="">Basic</label><br>
                                    <input type="checkbox" id="basic_button" class="js-switch" <?= isset($res[0]['basic']) && $res[0]['basic'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="basic" name="basic" value="<?= isset($res[0]['basic']) && $res[0]['basic'] == 1 ? 1 : 0 ?>">
                                 </div>
                             </div>
                             <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="">LifeTime</label><br>
                                    <input type="checkbox" id="lifetime_button" class="js-switch" <?= isset($res[0]['lifetime']) && $res[0]['lifetime'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="lifetime" name="lifetime" value="<?= isset($res[0]['lifetime']) && $res[0]['lifetime'] == 1 ? 1 : 0 ?>">
                                 </div>
                             </div>
                             <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="">Premium</label><br>
                                    <input type="checkbox" id="premium_button" class="js-switch" <?= isset($res[0]['premium']) && $res[0]['premium'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="premium" name="premium" value="<?= isset($res[0]['premium']) && $res[0]['premium'] == 1 ? 1 : 0 ?>">
                                 </div>
                             </div>
                      </div>
                      <div class="row">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Basic Joined Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['basic_joined_date']) ? $error['basic_joined_date'] : ''; ?>
                                    <input type="date" class="form-control" name="basic_joined_date" value="<?php echo $res[0]['basic_joined_date']; ?>">
                                </div>
                                    <div class="col-md-4">
                                    <label for="exampleInputEmail1">LifeTime Joined Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['lifetime_joined_date']) ? $error['lifetime_joined_date'] : ''; ?>
                                    <input type="date" class="form-control" name="lifetime_joined_date" value="<?php echo $res[0]['lifetime_joined_date']; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Premium Joined Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['premium_joined_date']) ? $error['premium_joined_date'] : ''; ?>
                                    <input type="date" class="form-control" name="premium_joined_date" value="<?php echo $res[0]['premium_joined_date']; ?>">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                        <div class="form-group col-md-4">
                                    <label class="control-label">Status</label><i class="text-danger asterik">*</i><br>
                                    <div id="status" class="btn-group">
                                        <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="0" <?= ($res[0]['status'] == 0) ? 'checked' : ''; ?>> Not-verified
                                        </label>
                                        <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="1" <?= ($res[0]['status'] == 1) ? 'checked' : ''; ?>> Verified
                                        </label>
                                    </div>
                                </div>
                        </div>

                        <br>
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
<script>
    var changeCheckbox = document.querySelector('#basic_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#basic').val(1);

        } else {
            $('#basic').val(0);
        }
    };
</script>
<script>
    var changeCheckbox = document.querySelector('#lifetime_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#lifetime').val(1);

        } else {
            $('#lifetime').val(0);
        }
    };
</script>
<script>
    var changeCheckbox = document.querySelector('#premium_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#premium').val(1);

        } else {
            $('#premium').val(0);
        }
    };
</script>