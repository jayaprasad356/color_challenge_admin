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

	$jobs_id = $db->escapeString(($_POST['jobs_id']));
	$position = $db->escapeString(($_POST['position']));
	$income = $db->escapeString(($_POST['income']));
	$error = array();

{

	$sql_query = "UPDATE jobs_income SET jobs_id='$jobs_id',position='$position',income='$income'  WHERE id =  $ID";
$db->sql($sql_query);
$update_result = $db->getResult();
if (!empty($update_result)) {
   $update_result = 0;
} else {
   $update_result = 1;
}

// check update result
if ($update_result == 1) {
   $error['update_jobs'] = " <section class='content-header'><span class='label label-success'>Jobs Income updated Successfully</span></section>";
} else {
   $error['update_jobs'] = " <span class='label label-danger'>Failed to Update</span>";
}
}
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM jobs_income WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();


if (isset($_POST['btnCancel'])) { ?>
<script>
window.location.href = "jobs_income.php";
</script>
<?php } ?>

<section class="content-header">
	<h1>
		Edit Jobs Income<small><a href='jobs_income.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Jobs Income</a></small></h1>
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
                            <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Select Jobs</label> <i class="text-danger asterik">*</i>
                                    <select id='jobs_id' name="jobs_id" class='form-control'onchange="updatePositions()">
                                           <option value="">--Select--</option>
                                                <?php
                                                  $sql = "SELECT id, total_slots FROM `jobs`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['jobs_id'] ? 'selected="selected"' : '';?>><?= $value['id'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
                                  </div>
                                </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                <div class='col-md-6'>
                                  <label for="exampleInputtitle">Position</label> <i class="text-danger asterik">*</i>
                                    <select id='position' name="position" class='form-control'>
                                     <option value=""></option>

                                      <?php
                                        $selectedJobId = $res[0]['jobs_id'];

                                        $totalSlots = 0;
                                         foreach ($result as $value) {
                                         if ($value['id'] == $selectedJobId) {
                                         $totalSlots = $value['total_slots'];
                                          break;
                                           }
                                          }
                                          for ($i = 1; $i <= $totalSlots; $i++) {
                                          ?>
                                         <option value='<?= $i ?>' <?= $i == $res[0]['position'] ? 'selected="selected"' : ''; ?>>
                                           <?= $i ?>
                                         </option>
                                         <?php } ?>
                                      </select>
                                   </div>

                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Income</label><i class="text-danger asterik">*</i><?php echo isset($error['income']) ? $error['income'] : ''; ?>
                                    <input type="number" class="form-control" name="income" value="<?php echo $res[0]['income']?>">
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
    function updatePositions() {
        var selectedJobId = document.getElementById('jobs_id').value;
        var positionsDropdown = document.getElementById('position');
        positionsDropdown.innerHTML = '<option value="">--Select--</option>';

        if (selectedJobId !== "") {
            var totalSlots = <?php echo json_encode($result); ?>.find(job => job.id == selectedJobId).total_slots;

            for (var i = 1; i <= totalSlots; i++) {
                var option = document.createElement("option");
                option.value = i;
                option.text = i;

                // Set the selected attribute if the value matches the stored value in the database
                if (i == <?php echo $res[0]['position']; ?>) {
                    option.selected = true;
                }

                positionsDropdown.appendChild(option);
            }
        }
    }
</script>
