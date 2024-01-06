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

	$total_slots = $db->escapeString(($_POST['total_slots']));
        $client_id = $db->escapeString(($_POST['client_id']));
        $title = $db->escapeString(($_POST['title']));
        $description = $db->escapeString(($_POST['description']));
        $appli_fees = $db->escapeString(($_POST['appli_fees']));
        $spots_left = $db->escapeString(($_POST['spots_left']));
        $highest_income = $db->escapeString(($_POST['highest_income']));
	$error = array();

{

	$sql_query = "UPDATE jobs SET total_slots='$total_slots',client_id='$client_id',title='$title',description='$description',appli_fees='$appli_fees',spots_left='$spots_left',highest_income='$highest_income'  WHERE id =  $ID";
$db->sql($sql_query);
$update_result = $db->getResult();
if (!empty($update_result)) {
   $update_result = 0;
} else {
   $update_result = 1;
}

// check update result
if ($update_result == 1) {
   $error['update_jobs'] = " <section class='content-header'><span class='label label-success'>Jobs updated Successfully</span></section>";
} else {
   $error['update_jobs'] = " <span class='label label-danger'>Failed to Update</span>";
}
}
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM jobs WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();


if (isset($_POST['btnCancel'])) { ?>
<script>
window.location.href = "jobs.php";
</script>
<?php } ?>

<section class="content-header">
	<h1>
		Edit Jobs<small><a href='jobs.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Jobs</a></small></h1>
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
                                    <label for="exampleInputEmail1">Title</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="title"  value="<?php echo $res[0]['title']; ?>">
                                </div>
                                <div class="col-md-6">
                                            <label for="exampleInputEmail1">Description</label> <i class="text-danger asterik">*</i><?php echo isset($error['description']) ? $error['description'] : ''; ?>
                                            <textarea  type="text" rows="3" class="form-control" name="description"><?php echo $res[0]['description']?></textarea readonly>
                                    </div>
                                </div>
                                </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Total Slots</label> <i class="text-danger asterik">*</i>
                                        <input type="text" class="form-control" name="total_slots"  value="<?php echo $res[0]['total_slots']; ?>">
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Application Fees</label> <i class="text-danger asterik">*</i>
                                        <input type="text" class="form-control" name="appli_fees"  value="<?php echo $res[0]['appli_fees']; ?>">
                                </div>
                            </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Spots Left</label> <i class="text-danger asterik">*</i>
                                        <input type="text" class="form-control" name="spots_left"  value="<?php echo $res[0]['spots_left']; ?>">
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Highest Income</label> <i class="text-danger asterik">*</i>
                                        <input type="text" class="form-control" name="highest_income"  value="<?php echo $res[0]['highest_income']; ?>">
                                    </div>
                            </div> 
                            <br>
                            
                            <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Select Clients</label> <i class="text-danger asterik">*</i>
                                    <select id='client_id' name="client_id" class='form-control'>
                                           <option value="">--Select--</option>
                                                <?php
                                                $sql = "SELECT * FROM `clients`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['client_id'] ? 'selected="selected"' : '';?>><?= $value['name'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
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