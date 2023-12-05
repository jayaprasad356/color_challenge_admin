<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php

if (isset($_GET['id'])) {
    $ID = $db->escapeString($_GET['id']);
} else {
    // $ID = "";
    return false;
    exit(0);
}
if (isset($_POST['btnEdit'])) {

             $title = $db->escapeString(($_POST['title']));
             $description = $db->escapeString(($_POST['description']));
             $datetime = $db->escapeString(($_POST['datetime']));
             $reply = $db->escapeString(($_POST['reply']));
             $preferences = $db->escapeString(($_POST['preferences']));
             $status = $db->escapeString(($_POST['status']));
             $error = array();

     {

        $sql_query = "UPDATE query SET title='$title',description='$description',datetime='$datetime',status='$status',reply = '$reply',preferences = '$preferences' WHERE id =  $ID";
        $db->sql($sql_query);
        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        // check update result
        if ($update_result == 1) {
            $error['update_query'] = " <section class='content-header'><span class='label label-success'>Query updated Successfully</span></section>";
        } else {
            $error['update_query'] = " <span class='label label-danger'>Failed to Update</span>";
        }
    }
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM query WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();

$user_id = $res[0]['user_id'];
$sql_query_user = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql_query_user);
$result = $db->getResult();


if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "query_list.php";
    </script>
<?php } ?>
<section class="content-header">
    <h1>
        Edit Query<small><a href='query_list.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Query</a></small></h1>
    <small><?php echo isset($error['update_query']) ? $error['update_query'] : ''; ?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <!-- Main row -->

    <div class="row">
        <div class="col-md-10">

            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <!-- /.box-header -->
                <!-- form start -->
                <form id="edit_query_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name']; ?>" readonly>
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Mobile</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="mobile" value="<?php echo $result[0]['mobile']; ?>" readonly>
                                </div>
                            </div> 
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Date Time</label><i class="text-danger asterik">*</i>
                                    <input type="datetime-local" class="form-control" name="datetime" value="<?php echo $res[0]['datetime']; ?>">
                                </div>
                                <div class='col-md-6'>
                                <label for="exampleInputEmail1">Title</label> <i class="text-danger asterik">*</i>
                                    <select id='title' name="title" class='form-control'>
                                    <option value=''>--Select--</option>
                                    <option value='Register Issue' <?php if ($res[0]['title'] == 'Register Issue') echo 'selected'; ?>>Register Issue</option>
                                      <option value='Otp Issue' <?php if ($res[0]['title'] == 'Otp Issue') echo 'selected'; ?>>Otp Issue</option>
                                      <option value='Ads Issue' <?php if ($res[0]['title'] == 'Ads Issue') echo 'selected'; ?>>Ads Issue</option>
                                      <option value='Withdrawal Issue' <?php if ($res[0]['title'] == 'Withdrawal Issue') echo 'selected'; ?>>Withdrawal Issue</option>
                                      <option value='Refer Bonus Issue' <?php if ($res[0]['title'] == 'Refer Bonus Issue') echo 'selected'; ?>>Refer Bonus Issue</option>
                                      <option value='Other Issue' <?php if ($res[0]['title'] == 'Other Issue') echo 'selected'; ?>>Other Issue</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                            <br>
                         <div class="row">
                            <div class="form-group">
                            <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Reply</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="reply" autocomplete="on" value="<?php echo $res[0]['reply']; ?>">
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Description</label> <i class="text-danger asterik">*</i>
                                    <textarea type="text" rows="3" class="form-control" name="description" ><?php echo $res[0]['description']?></textarea>
                                </div>
                            </div> 
                        </div>
                       <br>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label">Status</label><i class="text-danger asterik">*</i><br>
                                <div id="status" class="btn-group">
                                    <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="0" <?= ($res[0]['status'] == 0) ? 'checked' : ''; ?>> pending
                                    </label>
                                    <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="1" <?= ($res[0]['status'] == 1) ? 'checked' : ''; ?>> Completed
                                    </label>
                                    <label class="btn btn-danger" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                        <input type="radio" name="status" value="2" <?= ($res[0]['status'] == 2) ? 'checked' : ''; ?>> Cancelled
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">preferences</label> <i class="text-danger asterik">*</i>
                                    <select id='preferences' name="preferences" class='form-control'>
                                    <option value=''>--Select--</option>
                                    <option value='low' <?php if ($res[0]['preferences'] == 'low') echo 'selected'; ?>>low</option>
                                      <option value='medium' <?php if ($res[0]['preferences'] == 'medium') echo 'selected'; ?>>medium</option>
                                      <option value='high' <?php if ($res[0]['preferences'] == 'high') echo 'selected'; ?>>high</option>
                                    </select>
                            </div>
						</div>
                        <br>
                      
                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>
                    </div>
                    </div><!-- /.box-body -->
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>