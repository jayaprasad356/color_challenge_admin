<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php
if (isset($_POST['btnAdd'])) {

        $title = $db->escapeString(($_POST['title']));
        $description = $db->escapeString(($_POST['description']));
        $link = $db->escapeString(($_POST['link']));
        $error = array();
       
     
        if (empty($title)) {
            $error['title'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($description)) {
            $error['description'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($link)) {
            $error['link'] = " <span class='label label-danger'>Required!</span>";
        }
       
       if (!empty($title)&& !empty($description)&& !empty($link)) 
       {
           
            $sql_query = "INSERT INTO rewards (title,description,link)VALUES('$title','$description','$link')";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }

            if ($result == 1) {
                
                $error['add_jobs'] = "<section class='content-header'>
                                                <span class='label label-success'>Rewards Added Successfully</span> </section>";
            } else {
                $error['add_jobs'] = " <span class='label label-danger'>Failed</span>";
            }
            }
        }
?>
<section class="content-header">
    <h1>Add New Rewards <small><a href='rewards.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Rewards</a></small></h1>

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
                                
                                <div class='col-md-6'>
                                        <label for="exampleInputEmail1">Link</label> <i class="text-danger asterik">*</i><?php echo isset($error['link']) ? $error['link'] : ''; ?>
                                        <input type="text" class="form-control" name="link" id="link" required>
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
