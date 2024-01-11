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
        $status = $db->escapeString(($_POST['status']));
	$error = array();

	$sql_query = "UPDATE jobs SET total_slots='$total_slots',client_id='$client_id',title='$title',description='$description',appli_fees='$appli_fees',spots_left='$spots_left',highest_income='$highest_income',status='$status'  WHERE id =  $ID";
    $db->sql($sql_query);
    $result = $db->getResult();             
    if (!empty($result)) {
        $error['update_jobs'] = " <span class='label label-danger'>Failed</span>";
    } else {
        $error['update_jobs'] = " <span class='label label-success'>Jobs Updated Successfully</span>";
    }

    if ($_FILES['ref_image']['size'] != 0 && $_FILES['ref_image']['error'] == 0 && !empty($_FILES['ref_image'])) {
    
        $extension = pathinfo($_FILES["ref_image"]["name"])['extension'];

        $result = $fn->validate_image($_FILES["ref_image"]);
        $target_path = 'upload/images/';
        
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . "" . $filename;
        if (!move_uploaded_file($_FILES["ref_image"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Can not upload image.</p>';
            return false;
            exit();
        }
        if (!empty($old_image) && file_exists($old_image)) {
            unlink($old_image);
        }

        $upload_image = 'upload/images/' . $filename;
        $sql = "UPDATE jobs SET `ref_image`='$upload_image' WHERE `id`='$ID'";
        $db->sql($sql);

        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        if ($update_result == 1) {
            $error['update_jobs'] = " <section class='content-header'><span class='label label-success'>Jobs updated Successfully</span></section>";
        } else {
            $error['update_jobs'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}

$data = array();

$sql_query = "SELECT * FROM jobs WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();

function getAppliedJobCount($jobId, $db) {
    $sql = "SELECT COUNT(*) as count FROM applied_jobs WHERE jobs_id = $jobId";
    $db->sql($sql);
    $result = $db->getResult();
    return $result[0]['count'];
}


// Assuming $jobId is the ID of the current job
$jobId = $res[0]['id'];
$appliedJobCount = getAppliedJobCount($jobId, $db);
$spotsLeft = $res[0]['total_slots'] - $appliedJobCount;


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
                                <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Application Fees</label> <i class="text-danger asterik">*</i>
                                        <input type="number" class="form-control" name="appli_fees"  value="<?php echo $res[0]['appli_fees']; ?>">
                                </div>
                             </div>
                             </div>    
                             <br>                  
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Total Slots</label> <i class="text-danger asterik">*</i>
                                        <input type="number" class="form-control" name="total_slots"  value="<?php echo $res[0]['total_slots']; ?>" readonly>
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Highest Income</label> <i class="text-danger asterik">*</i>
                                        <input type="number" class="form-control" name="highest_income"  value="<?php echo $res[0]['highest_income']; ?>">
                                    </div>
                               </div>
                            </div>
                            <br>
                            <div class="row">
                            <div class='col-md-6'>
                              <label for="exampleInputEmail1">Spots Left</label> <i class="text-danger asterik">*</i>
                                <input type="number" class="form-control" name="spots_left" value="<?php echo $spotsLeft; ?>">
                                </div>
                                <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputFile">Reference Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['ref_image']) ? $error['ref_image'] : ''; ?>
                                    <input type="file" name="ref_image" onchange="readURL(this);" accept="image/png, image/jpeg" id="ref_image" /><br>
                                    <img id="blah" src="<?php echo $res[0]['ref_image']; ?>" alt="" width="150" height="200" <?php echo empty($res[0]['ref_image']) ? 'style="display: none;"' : ''; ?> />
                                </div>
                            </div>    
                            </div>
                                   
                            <br>
                            <div class="row">   
                            <div class="form-group col-md-6">
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
                            <div class="form-group col-md-6">
                                <label class="control-label">Status</label><i class="text-danger asterik">*</i><br>
                                <div id="status" class="btn-group">
                                    <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="1" <?= ($res[0]['status'] == 1) ? 'checked' : ''; ?>> Activated
                                    </label>
                                    <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="0" <?= ($res[0]['status'] == 0) ? 'checked' : ''; ?>> Deactivated
                                    </label>
                                </div>
                            </div>  
                            </div>             
                            <br>   
                            <div class="form-group">
                                <label for="description">Description :</label> <i class="text-danger asterik">*</i><?php echo isset($error['description']) ? $error['description'] : ''; ?>
                                <textarea name="description" id="description" class="form-control" rows="8"><?php echo $res[0]['description']; ?></textarea>
                                <script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
                                <script type="text/javascript">
                                    CKEDITOR.replace('description');
                                </script>
                            </div>      
                                        
                            <br>              
                            </div>  
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_slide_form').validate({
        rules: {
            description: {
                required: function(textarea) {
                    CKEDITOR.instances[textarea.id].updateElement();
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
                    return editorcontent.length === 0;
                }
            }
        }
    });
</script>