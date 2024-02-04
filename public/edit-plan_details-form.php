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

    $name = $db->escapeString(($_POST['name']));
    $validity = $db->escapeString(($_POST['validity']));
    $daily_income = $db->escapeString(($_POST['daily_income']));
	$error = array();

{

$sql_query = "UPDATE plan_details SET name='$name',validity='$validity',daily_income='$daily_income' WHERE id =  $ID";
$db->sql($sql_query);
$update_result = $db->getResult();
if (!empty($update_result)) {
   $update_result = 0;
} else {
   $update_result = 1;
}

// check update result
if ($update_result == 1) {
   $error['update_jobs'] = " <section class='content-header'><span class='label label-success'>Plan Details updated Successfully</span></section>";
} else {
   $error['update_jobs'] = " <span class='label label-danger'>Failed to Update</span>";
}
}
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM plan_details WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();


if (isset($_POST['btnCancel'])) { ?>
<script>
window.location.href = "plan_details.php";
</script>
<?php } ?>

<section class="content-header">
	<h1>
		Edit Plan Details<small><a href='plan_details.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Plan Details</a></small></h1>
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
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Name</label><i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Validity</label><i class="text-danger asterik">*</i><?php echo isset($error['validity']) ? $error['validity'] : ''; ?>
                                    <input type="number" class="form-control" name="validity" value="<?php echo $res[0]['validity']?>">
                                </div>
                            </div>
                         </div>
                         <br>
                         <div class="row">
                                <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Daily Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['daily_income']) ? $error['daily_income'] : ''; ?>
                                        <input type="number" class="form-control" name="daily_income" value="<?php echo $res[0]['daily_income']?>">
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