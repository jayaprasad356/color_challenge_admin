<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnAdd'])) {

    $manager_id = $db->escapeString(($_POST['manager_id']));
    $name = $db->escapeString($_POST['name']);
    $mobile = $db->escapeString($_POST['mobile']);
    $balance = $db->escapeString($_POST['balance']);

    if (empty($manager_id)) {
        $error['manager_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($name)) {
        $error['name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($mobile)) {
        $error['mobile'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($balance)) {
        $error['balance'] = " <span class='label label-danger'>Required!</span>";
    }
    if (!empty($manager_id)  && !empty($name) && !empty($mobile) && !empty($balance)) {
        $sql_query = "INSERT INTO users (manager_id,name,mobile,balance)VALUES('$manager_id','$name','$mobile','$balance')";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }
        if ($result == 1) {

            $error['add_project'] = "<section class='content-header'>
                                                <span class='label label-success'>Project Added Successfully</span> </section>";
        } else {
            $error['add_project'] = " <span class='label label-danger'>Failed</span>";
        }
    }
}
?>
<section class="content-header">
    <h1>Add Users <small><a href='users.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Users</a></small></h1>
    <?php echo isset($error['add_project']) ? $error['add_project'] : ''; ?>
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
                <form name="add_project_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">

                                <div class="col-md-6">
                                    <label for="">Manager ID</label> <i class="text-danger asterik">*</i>
                                    <select id='manager_id' name="manager_id" class='form-control' required>
                                        <option value="">select</option>
                                        <?php
                                        $sql = "SELECT id,name FROM `managers`";
                                        $db->sql($sql);
                                        $result = $db->getResult();
                                        foreach ($result as $value) {
                                        ?>
                                            <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"> Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"> Mobile</label> <i class="text-danger asterik">*</i><?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>
                                    <input type="text" rows="4" class="form-control" name="mobile" required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Balance</label> <i class="text-danger asterik">*</i><?php echo isset($error['balance']) ? $error['balance'] : ''; ?>
                                    <input type="text" rows="4" class="form-control" name="balance" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Add</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_project_form').validate({

        ignore: [],
        debug: false,
        rules: {
            name: "required",
            client_name: "required",
            description: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>

<!--code for page clear-->
<script>
    function refreshPage() {
        window.location.reload();
    }
</script>

<?php $db->disconnect(); ?>