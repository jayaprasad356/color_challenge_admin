<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php
if (isset($_POST['btnAdd'])) {

        $total_slots = $db->escapeString(($_POST['total_slots']));
        $spots_left = $db->escapeString(($_POST['spots_left']));
        $client_id = $db->escapeString(($_POST['client_id']));
        $title = $db->escapeString(($_POST['title']));
        $description = $db->escapeString(($_POST['description']));
        $appli_fees = $db->escapeString(($_POST['appli_fees']));
        $highest_income = $db->escapeString(($_POST['highest_income']));
        $status = $db->escapeString(($_POST['status']));
        $error = array();
       
        if (empty($total_slots)) {
            $error['total_slots'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($client_id)) {
            $error['client_id'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($title)) {
            $error['title'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($description)) {
            $error['description'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($appli_fees)) {
            $error['appli_fees'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($status)) {
            $error['status'] = " <span class='label label-danger'>Required!</span>";
        }

         // Validate and process the image upload
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

        $upload_image = 'upload/images/' . $filename;
        $sql = "INSERT INTO jobs (total_slots, client_id,ref_image,title,description,appli_fees,highest_income,spots_left,status) VALUES ('$total_slots','$client_id', '$upload_image','$title','$description','$appli_fees','$highest_income','$spots_left','$status')";
        $db->sql($sql);
    } else {
            $sql_query = "INSERT INTO jobs (total_slots,client_id,title,description,appli_fees,highest_income,spots_left,status)VALUES('$total_slots','$client_id','$title','$description','$appli_fees','$highest_income','$spots_left','$status')";
            $db->sql($sql);
        }
    
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }
    
        if ($result == 1) {
            $error['add_jobs'] = "<section class='content-header'>
                                                <span class='label label-success'>Jobs Added Successfully</span> </section>";
        } else {
            $error['add_jobs'] = " <span class='label label-danger'>Failed</span>";
        }
    }
    ?>
<section class="content-header">
    <h1>Add New Jobs <small><a href='jobs.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Jobs</a></small></h1>

    <?php echo isset($error['add_jobs']) ? $error['add_jobs'] : ''; ?>
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
                <form name="add_slide_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                      <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Title</label><i class="text-danger asterik">*</i><?php echo isset($error['title']) ? $error['title'] : ''; ?>
                                    <input type="text" class="form-control" name="title" id="title" required>
                                </div>
                                <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Application Fees</label> <i class="text-danger asterik">*</i><?php echo isset($error['appli_fees']) ? $error['appli_fees'] : ''; ?>
                                        <input type="number" class="form-control" name="appli_fees" id="appli_fees" required>
                                    </div>
                            </div>
                         </div>
                                <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Total Slots</label> <i class="text-danger asterik">*</i><?php echo isset($error['total_slots']) ? $error['total_slots'] : ''; ?>
                                        <input type="number" class="form-control" name="total_slots" id="total_slots" required>
                                    </div>
                                  </div>
                                  <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Highest Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['highest_income']) ? $error['highest_income'] : ''; ?>
                                        <input type="number" class="form-control" name="highest_income" id="highest_income" required>
                                    </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Spots Left</label> <i class="text-danger asterik">*</i><?php echo isset($error['spots_left']) ? $error['spots_left'] : ''; ?>
                                        <input type="number" class="form-control" name="spots_left" id="spots_left" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputFile">Reference Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['ref_image']) ? $error['ref_image'] : ''; ?>
                                        <input type="file" name="ref_image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="ref_image" required/><br>
                                        <img id="blah" src="#" alt="" />
                                    </div>
                            </div> 
                            <br>
                            <div class="row">
                            <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Select Clients</label> <i class="text-danger asterik">*</i>
                                    <select id='branch_id' name="client_id" class='form-control'>
                                           <option value="">--Select--</option>
                                                <?php
                                                $sql = "SELECT id,name FROM `clients`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                    ?>
                                                    <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                                <?php } ?>
                                            </select>
                                    </div>
                                           
                                    <div class="form-group col-md-6">
                                <label class="control-label">Status</label><i class="text-danger asterik">*</i><br>
                                <div id="status" class="btn-group">
                                    <label class="btn btn-primary active" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="0">Activated
                                    </label>
                                    <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="1"> Deactivated
                                    </label>
                                </div>
                            </div>
                            </div>  
                            <br>   
                            <div class="form-group">
                                <label for="description">Description :</label> <i class="text-danger asterik">*</i><?php echo isset($error['description']) ? $error['description'] : ''; ?>
                                <textarea name="description" id="description" class="form-control" rows="8"></textarea>
                                <script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
                                <script type="text/javascript">
                                    CKEDITOR.replace('description');
                                </script>
                            </div>   

                            <br>                     
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
    document.addEventListener("DOMContentLoaded", function () {
        // Set the "Activated" radio button and button label as active by default
        document.querySelector('input[name="status"][value="0"]').checked = true;
        document.querySelector('label.btn-primary').classList.add('active');
    });
</script>

<?php $db->disconnect(); ?>
