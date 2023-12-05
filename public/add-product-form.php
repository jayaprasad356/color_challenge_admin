<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    $name = $db->escapeString($_POST['name']);
    $description = $db->escapeString($_POST['description']);
    $category_id = $db->escapeString($_POST['category_id']);
    $ads = $db->escapeString($_POST['ads']);
    $original_price = $db->escapeString($_POST['original_price']);

    if (empty($name)) {
        $error['name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($description)) {
        $error['description'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($ads)) {
        $error['ads'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($original_price)) {
        $error['original_price'] = " <span class='label label-danger'>Required!</span>";
    }



    // Validate and process the image upload
    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
        $extension = pathinfo($_FILES["image"]["name"])['extension'];

        $result = $fn->validate_image($_FILES["image"]);
        $target_path = 'upload/images/';

        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . "" . $filename;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Can not upload image.</p>';
            return false;
            exit();
        }

        $upload_image = 'upload/images/' . $filename;
        $sql = "INSERT INTO product (name, description,image,category_id,ads,original_price) VALUES ('$name','$description', '$upload_image','$category_id','$ads','$original_price')";
        $db->sql($sql);
    } else {
        // Image is not uploaded or empty, insert only the name
        $sql = "INSERT INTO product (name,description,category_id,ads,original_price) VALUES ('$name','$description','$category_id','$ads','$original_price')";
        $db->sql($sql);
    }

    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }

    if ($result == 1) {
        $error['add_slide'] = "<section class='content-header'>
                                            <span class='label label-success'>Product Added Successfully</span> </section>";
    } else {
        $error['add_slide'] = " <span class='label label-danger'>Failed</span>";
    }
}
?>

<section class="content-header">
    <h1>Add Product <small><a href='product.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Product</a></small></h1>

    <?php echo isset($error['add_slide']) ? $error['add_slide'] : ''; ?>
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
                                    <div class='col-md-4'>
                                        <label for="exampleInputEmail1">Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                        <input type="text" class="form-control" name="name" id="name" required>
                                    </div>
                                    <div class="col-md-4">
                                    <label for="exampleInputEmail1">Description</label><i class="text-danger asterik">*</i><?php echo isset($error['description']) ? $error['description'] : ''; ?>
                                    <textarea  rows="3" type="number" class="form-control" name="description" required></textarea>
                                </div>
                                </div>
                            <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Select category</label> <i class="text-danger asterik">*</i>
                                    <select id='category_id' name="category_id" class='form-control'>
                                             <option value="">--Select--</option>
                                                <?php
                                       $sql = "SELECT id,name FROM `category`";
                                       $db->sql($sql);
                                       $result = $db->getResult();
                                       foreach ($result as $value) {
                                       ?>
                                           <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                       <?php } ?>
                                   </select>
                            </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label for="exampleInputFile">Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                        <input type="file" name="image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="image" required/><br>
                                        <img id="blah" src="#" alt="" />
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                        <label for="exampleInputEmail1">Ads</label> <i class="text-danger asterik">*</i><?php echo isset($error['ads']) ? $error['ads'] : ''; ?>
                                        <input type="number" class="form-control" name="ads" id="ads" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="exampleInputEmail1">Original Price</label> <i class="text-danger asterik">*</i><?php echo isset($error['original_price']) ? $error['original_price'] : ''; ?>
                                        <input type="number" class="form-control" name="original_price" id="original_price" required>
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
    $('#add_mahabharatham_form').validate({

        ignore: [],
        debug: false,
        rules: {
            name: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200)
                    .css('display', 'block'); // Show the image after setting the source
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
