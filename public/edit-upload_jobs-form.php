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

	$user_name = $db->escapeString(($_POST['user_name']));
	$company_name = $db->escapeString(($_POST['company_name']));
	$title = $db->escapeString(($_POST['title']));
	$description = $db->escapeString(($_POST['description']));
	$deadline = $db->escapeString(($_POST['deadline']));
	$price = $db->escapeString(($_POST['price']));
	$error = array();

{

	$sql_query = "UPDATE upload_jobs SET user_name='$user_name',company_name='$company_name',title='$title',description='$description',deadline='$deadline',price='$price' WHERE id =  $ID";
$db->sql($sql_query);
$update_result = $db->getResult();
if (!empty($update_result)) {
   $update_result = 0;
} else {
   $update_result = 1;
}

// check update result
if ($update_result == 1) {
   $error['update_jobs'] = " <section class='content-header'><span class='label label-success'>Upload Jobs updated Successfully</span></section>";
} else {
   $error['update_jobs'] = " <span class='label label-danger'>Failed to Update</span>";
}
}
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM upload_jobs WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();


if (isset($_POST['btnCancel'])) { ?>
<script>
window.location.href = "upload_jobs.php";
</script>
<?php } ?>

<section class="content-header">
	<h1>
		Edit Upload Jobs<small><a href='upload_jobs.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Upload Jobs</a></small></h1>
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
                                        <label for="exampleInputEmail1">User Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['user_name']) ? $error['user_name'] : ''; ?>
                                        <input type="text" class="form-control" name="user_name" value="<?php echo $res[0]['user_name']?>">
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Company Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['company_name']) ? $error['company_name'] : ''; ?>
                                        <input type="text" class="form-control" name="company_name" value="<?php echo $res[0]['company_name']?>">
                                    </div>
                                </div>
                                </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Title</label><i class="text-danger asterik">*</i><?php echo isset($error['title']) ? $error['title'] : ''; ?>
                                    <input type="text" class="form-control" name="title" value="<?php echo $res[0]['title']?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Description</label><i class="text-danger asterik">*</i><?php echo isset($error['description']) ? $error['description'] : ''; ?>
									<textarea type="text" rows="3" class="form-control" name="description" ><?php echo $res[0]['description']?></textarea>
                                </div>
                                </div>
                                
                                <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Deadline</label> <i class="text-danger asterik">*</i><?php echo isset($error['deadline']) ? $error['deadline'] : ''; ?>
                                        <input type="date" class="form-control" name="deadline" value="<?php echo $res[0]['deadline']?>">
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Price</label> <i class="text-danger asterik">*</i><?php echo isset($error['price']) ? $error['price'] : ''; ?>
                                        <input type="number" class="form-control" name="price" value="<?php echo $res[0]['price']?>">
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