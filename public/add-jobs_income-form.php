<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php
if (isset($_POST['btnAdd'])) {

    $jobs_id = $db->escapeString(($_POST['jobs_id']));
	$position = $db->escapeString(($_POST['position']));
	$income = $db->escapeString(($_POST['income']));
	$error = array();

       
        if (empty($jobs_id)) {
            $error['jobs_id'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($position)) {
            $error['position'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($income)) {
            $error['income'] = " <span class='label label-danger'>Required!</span>";
        }
       
       
       if (!empty($jobs_id) && !empty($position) && !empty($income)) 
       {
           
            $sql_query = "INSERT INTO jobs_income (jobs_id,position,income)VALUES('$jobs_id','$position','$income')";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }

            if ($result == 1) {
                
                $error['add_languages'] = "<section class='content-header'>
                                                <span class='label label-success'>Jobs Income Added Successfully</span> </section>";
            } else {
                $error['add_languages'] = " <span class='label label-danger'>Failed</span>";
            }
            }
        }
?>
<section class="content-header">
    <h1>Add New Jobs Income <small><a href='jobs_income.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Jobs Income</a></small></h1>

    <?php echo isset($error['add_languages']) ? $error['add_languages'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-10">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form url="add-languages-form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                       <div class="row">
                         <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Select Jobs</label> <i class="text-danger asterik">*</i>
                                    <select id='jobs_id' name="jobs_id" class='form-control' onchange="updatePositions()">
                                             <option value="">--Select--</option>
                                             <?php
                                              $sql = "SELECT id, total_slots FROM `jobs`";
                                              $db->sql($sql);
                                               $result = $db->getResult();
                                              foreach ($result as $value) {
                                                  ?>
                                             <option value='<?= $value['id'] ?>'><?= $value['id'] ?></option>
                                            <?php } ?>
                                   </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputtitle">Position</label> <i class="text-danger asterik">*</i>
                                    <select id='position' name="position" class='form-control'>
                                     <option value="">--Select--</option>
                                    </select>
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputtitle">Income</label> <i class="text-danger asterik">*</i>
                                    <input type="income" class="form-control" name="income" required>
                                </div>
                            </div>
                        </div>
                     </div>

                        <br>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Submit</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>
                <div id="result"></div>

            </div><!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_leave_form').validate({

        ignore: [],
        debug: false,
        rules: {
        reason: "required",
            date: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>
<script>
    $(document).ready(function () {
        $('#user_id').select2({
        width: 'element',
        placeholder: 'Type in name to search',

    });
    });

    if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

</script>

<!--code for page clear-->
<script>
    function refreshPage(){
    window.location.reload();
} 
</script>
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
                positionsDropdown.appendChild(option);
            }
        }
    }
</script>
<?php $db->disconnect(); ?>
