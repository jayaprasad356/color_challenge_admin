<?php
include_once('includes/functions.php');
date_default_timezone_set('Asia/Kolkata');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_GET['id'])) {
    $ID = $db->escapeString($fn->xss_clean($_GET['id']));
} else {
    // $ID = "";
    return false;
    exit(0);
}
if (isset($_POST['btnUpdate'])) {
    $name = $db->escapeString($fn->xss_clean($_POST['name']));

    $sql = "UPDATE clients SET name='$name' WHERE id = '$ID'";
    $db->sql($sql);
    $update_result = $db->getResult();

    if (empty($update_result)) {
        $error['update_ads'] = "<span class='label label-success'>Clients updated Successfully</span>";
    } else {
        $error['update_ads'] = "<span class='label label-danger'>Failed to update</span>";
    }


    if (isset($_FILES['profile']['size']) && $_FILES['profile']['size'] != 0 && $_FILES['profile']['error'] == 0 && !empty($_FILES['profile'])) {
        // The "image" file is not empty, and there is no error in the file upload
        if (isset($_POST['old_image'])) {
            $old_image = $db->escapeString($_POST['old_image']);
        } else {
            $old_image = '';
        }
    
        $extension = pathinfo($_FILES["profile"]["name"])['extension'];
        $result = $fn->validate_image($_FILES["profile"]);
        $target_path = 'upload/images/';
    
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . "" . $filename;
    
        if (!move_uploaded_file($_FILES["profile"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Can not upload image.</p>';
            return false;
            exit();
        }
    
        if (!empty($old_image) && file_exists($old_image)) {
            undescription($old_image);
        }
    
        $upload_image = 'upload/images/' . $filename;
        $sql = "UPDATE clients SET `profile`='$upload_image' WHERE `id`='$ID'";
        $db->sql($sql);
    
        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }
    
        if ($update_result == 1) {
            $error['update_ads'] = " <section class='content-header'><span class='label label-success'>Clients updated Successfully</span></section>";
        } else {
            $error['update_ads'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}    

$data = array();

$sql_query = "SELECT * FROM `clients` WHERE id = '$ID'";
$db->sql($sql_query);
$res = $db->getResult();
foreach ($res as $row)
$data = $row;

?>
<section class="content-header">
<h1>Edit Clients <small><a href='clients.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Clients</a></small></h1>
    <?php echo isset($error['update_ads']) ? $error['update_ads'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>

</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <!-- /.box-header -->
                <!-- form start -->
                <form id='edit_ads_form' method="post" enctype="multipart/form-data">
                    <div class="box-body">
                    <div class="row">
                            <div class='col-md-8'>
                                    <label for="exampleInputEmail1">Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']; ?>">
                                </div> 
                            </div>
                            <br>
                        <div class="row">
                        <div class="form-group">
                                <div class="col-md-8">
                                    <label for="exampleInputFile">Profile</label> <i class="text-danger asterik">*</i><?php echo isset($error['profile']) ? $error['profile'] : ''; ?>
                                    <input type="file" name="profile" onchange="readURL(this);" accept="image/png, image/jpeg" id="image" /><br>
                                    <img id="blah" src="<?php echo $res[0]['profile']; ?>" alt="" width="150" height="200" <?php echo empty($res[0]['profile']) ? 'style="display: none;"' : ''; ?> />
                                </div>
                            </div>
                            </div>
                            <br>
                    </div>
                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Update" name="btnUpdate" />&nbsp;
                        <!-- <input type="reset" class="btn-danger btn" value="Clear" id="btnClear" /> -->
                        <!--<div  id="res"></div>-->
                    </div>
                </form>
            </div>
            <!-- /.box -->
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
                    .height(200)
                    .css('display', 'block');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>