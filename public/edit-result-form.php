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
	$user_jobs_id = $db->escapeString(($_POST['user_jobs_id']));
	$position = $db->escapeString(($_POST['position']));
	$income = $db->escapeString(($_POST['income']));
	$error = array();

{

	$sql_query = "UPDATE result SET user_id='$user_id',user_jobs_id='$user_jobs_id',position='$position',income='$income'  WHERE id =  $ID";
$db->sql($sql_query);
$update_result = $db->getResult();
if (!empty($update_result)) {
   $update_result = 0;
} else {
   $update_result = 1;
}

// check update result
if ($update_result == 1) {
   $error['update_jobs'] = " <section class='content-header'><span class='label label-success'>Result updated Successfully</span></section>";
} else {
   $error['update_jobs'] = " <span class='label label-danger'>Failed to Update</span>";
}
}
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM result WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();


if (isset($_POST['btnCancel'])) { ?>
<script>
window.location.href = "result.php";
</script>
<?php } ?>

<section class="content-header">
	<h1>
		Edit Result<small><a href='result.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Result</a></small></h1>
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
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">User ID</label> <i class="text-danger asterik">*</i><?php echo isset($error['user_id']) ? $error['user_id'] : ''; ?>
                                        <input type="text" class="form-control" name="user_id" value="<?php echo $res[0]['user_id']?>">
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">User Jobs ID</label> <i class="text-danger asterik">*</i><?php echo isset($error['user_jobs_id']) ? $error['user_jobs_id'] : ''; ?>
                                        <input type="text" class="form-control" name="user_jobs_id" value="<?php echo $res[0]['user_jobs_id']?>">
                                    </div>
                                </div>
                                </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Position</label><i class="text-danger asterik">*</i><?php echo isset($error['position']) ? $error['position'] : ''; ?>
                                    <input type="text" class="form-control" name="position" value="<?php echo $res[0]['position']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Income</label><i class="text-danger asterik">*</i><?php echo isset($error['income']) ? $error['income'] : ''; ?>
                                    <input type="text" class="form-control" name="income" value="<?php echo $res[0]['income']?>">
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