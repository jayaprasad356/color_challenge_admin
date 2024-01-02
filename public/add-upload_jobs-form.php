<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php
if (isset($_POST['btnAdd'])) {

        $user_name = $db->escapeString(($_POST['user_name']));
        $company_name = $db->escapeString(($_POST['company_name']));
        $title = $db->escapeString(($_POST['title']));
        $description = $db->escapeString(($_POST['description']));
        $deadline = $db->escapeString(($_POST['deadline']));
        $price = $db->escapeString(($_POST['price']));
        $error = array();
       
        if (empty($user_name)) {
            $error['user_name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($company_name)) {
            $error['company_name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($title)) {
            $error['title'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($description)) {
            $error['description'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($deadline)) {
            $error['deadline'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($price)) {
            $error['price'] = " <span class='label label-danger'>Required!</span>";
        }
       
       if (!empty($user_name) && !empty($company_name)&& !empty($title)&& !empty($description)&& !empty($deadline)&& !empty($price)) 
       {
           
            $sql_query = "INSERT INTO upload_jobs (user_name,company_name,title,description,deadline,price)VALUES('$user_name','$company_name','$title','$description','$deadline','$price')";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }

            if ($result == 1) {
                
                $error['add_jobs'] = "<section class='content-header'>
                                                <span class='label label-success'>Upload Jobs Added Successfully</span> </section>";
            } else {
                $error['add_jobs'] = " <span class='label label-danger'>Failed</span>";
            }
            }
        }
?>
<section class="content-header">
    <h1>Add New Upload Jobs <small><a href='upload_jobs.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Upload Jobs</a></small></h1>

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
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">User Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['user_name']) ? $error['user_name'] : ''; ?>
                                        <input type="text" class="form-control" name="user_name" id="user_name" required>
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Company Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['company_name']) ? $error['company_name'] : ''; ?>
                                        <input type="text" class="form-control" name="company_name" id="company_name" required>
                                    </div>
                                </div>
                                </div>
                            <br>
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
                                
                                <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Deadline</label> <i class="text-danger asterik">*</i><?php echo isset($error['deadline']) ? $error['deadline'] : ''; ?>
                                        <input type="date" class="form-control" name="deadline" id="deadline" required>
                                    </div>
                                    <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Price</label> <i class="text-danger asterik">*</i><?php echo isset($error['price']) ? $error['price'] : ''; ?>
                                        <input type="number" class="form-control" name="price" id="price" required>
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
