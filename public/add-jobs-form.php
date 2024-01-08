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
      
       
       if (!empty($total_slots) && !empty($client_id)&& !empty($title)&& !empty($description)&& !empty($appli_fees)&& !empty($highest_income)&& !empty($spots_left)) 
       {
           
            $sql_query = "INSERT INTO jobs (total_slots,client_id,title,description,appli_fees,highest_income,spots_left)VALUES('$total_slots','$client_id','$title','$description','$appli_fees','$highest_income','$spots_left')";
            $db->sql($sql_query);
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
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Description</label><i class="text-danger asterik">*</i><?php echo isset($error['description']) ? $error['description'] : ''; ?>
                                    <textarea  rows="3" type="number" class="form-control" name="description" required></textarea>
                                </div>
                                </div>
                                </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Total Slots</label> <i class="text-danger asterik">*</i><?php echo isset($error['total_slots']) ? $error['total_slots'] : ''; ?>
                                        <input type="text" class="form-control" name="total_slots" id="total_slots" required>
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Application Fees</label> <i class="text-danger asterik">*</i><?php echo isset($error['appli_fees']) ? $error['appli_fees'] : ''; ?>
                                        <input type="text" class="form-control" name="appli_fees" id="appli_fees" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Spots Left</label> <i class="text-danger asterik">*</i><?php echo isset($error['spots_left']) ? $error['spots_left'] : ''; ?>
                                        <input type="text" class="form-control" name="spots_left" id="spots_left" required>
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Highest Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['highest_income']) ? $error['highest_income'] : ''; ?>
                                        <input type="text" class="form-control" name="highest_income" id="highest_income" required>
                                    </div>
                            </div> 
                            </div> 
                            <br>
                            
                            <div class="form-group col-md-4">
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

<?php $db->disconnect(); ?>
