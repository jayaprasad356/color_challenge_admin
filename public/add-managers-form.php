<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnAdd'])) {

        $pin = $db->escapeString($_POST['pin']);
        $name = $db->escapeString($_POST['name']);
        $email = $db->escapeString($_POST['email']);
        $mobile = $db->escapeString($_POST['mobile']);


        if (empty($pin)) {
            $error['pin'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($name)) {
            $error['name'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($email)) {
            $error['email'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($mobile)) {
            $error['mobile'] = " <span class='label label-danger'>Required!</span>";
        }
       

      

        if (!empty($pin) && !empty($name) && !empty($email) && !empty($mobile)) 
        {
            $sql = "SELECT * FROM users WHERE pin = $pin";
            $db->sql($sql);
            $res = $db->getResult();
            $num = $db->numRows($res);
            if ($num >= 1){
                $error['add_user'] = " <span class='label label-danger'>Same Pin Not Allowed</span>";
    
            }
            else{
                $sql_query = "INSERT INTO managers (pin,name,email,mobile,status)VALUES('$pin','$name','$email','$mobile',0)";
                $db->sql($sql_query);
                $result = $db->getResult();
                if (!empty($result)) {
                    $result = 0;
                } else {
                    $result = 1;
                }
                if ($result == 1) {
                    $error['add_user'] = " <section class='content-header'><span class='label label-success'>User Added Successfully</span></section>";
                } else {
                    $error['add_user'] = " <span class='label label-danger'>Failed</span>";
                }


            }


            }
        }
?>
<section class="content-header">
    <h1>Add Managers <small><a href='managers.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Managers</a></small></h1>

    <?php echo isset($error['add_user']) ? $error['add_user'] : ''; ?>
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
                </div><!-- /.box-header -->
                <!-- form start -->
                <form name="add_user_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                            <div class="col-md-6">
                                    <label for="exampleInputEmail1">Pin</label><i class="text-danger asterik">*</i><?php echo isset($error['pin']) ? $error['pin'] : '';  ?>
                                     <input type="text" class="form-control" name="pin" value="<?php echo ''.rand(1000,10000);?>" readonly required>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"> Name</label><i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                     <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"> Mobile Number</label><i class="text-danger asterik">*</i><?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>
                                     <input type="number" class="form-control" name="mobile" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Email-Id:</label><i class="text-danger asterik">*</i><?php echo isset($error['email']) ? $error['email'] : ''; ?>
                                     <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Submit</button>
                        <input type="reset"  onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script>
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>
<!--code for page clear-->
<script>
    function refreshPage(){
    window.location.reload();
} 
</script>
<?php $db->disconnect(); ?>