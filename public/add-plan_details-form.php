<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php
if (isset($_POST['btnAdd'])) {

        $name = $db->escapeString(($_POST['name']));
        $validity = $db->escapeString(($_POST['validity']));
        $daily_income = $db->escapeString(($_POST['daily_income']));
        $error = array();
       
     
        if (empty($name)) {
            $error['name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($validity)) {
            $error['validity'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($daily_income)) {
            $error['daily_income'] = " <span class='label label-danger'>Required!</span>";
        }
       
       if (!empty($name)&& !empty($validity)&& !empty($daily_income)) 
       {
           
            $sql_query = "INSERT INTO plan_details (name,validity,daily_income)VALUES('$name','$validity','$daily_income')";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }

            if ($result == 1) {
                
                $error['add_jobs'] = "<section class='content-header'>
                                                <span class='label label-success'>Plan Details Added Successfully</span> </section>";
            } else {
                $error['add_jobs'] = " <span class='label label-danger'>Failed</span>";
            }
            }
        }
?>
<section class="content-header">
    <h1>Add New Plan Details <small><a href='plan_details.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Plan Details</a></small></h1>

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
                                    <label for="exampleInputEmail1">Name</label><i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Validity</label><i class="text-danger asterik">*</i><?php echo isset($error['validity']) ? $error['validity'] : ''; ?>
                                    <input type="number" class="form-control" name="validity" id="validity" required>
                                </div>
                                </div>
                             </div>
                             <br>
                             <div class="row">  
                                <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Daily Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['daily_income']) ? $error['daily_income'] : ''; ?>
                                        <input type="number" class="form-control" name="daily_income" id="daily_income" required>
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

<?php $db->disconnect(); ?>
