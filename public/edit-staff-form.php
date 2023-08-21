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
            $status = $db->escapeString($fn->xss_clean($_POST['status']));
            $join_date = $db->escapeString(($_POST['join_date']));
            $branch_id = $db->escapeString(($_POST['branch_id']));
            $name = $db->escapeString(($_POST['name']));
            $balance = $db->escapeString(($_POST['balance']));
            $sa_balance = $db->escapeString(($_POST['sa_balance']));
            $salary = $db->escapeString(($_POST['salary']));
            $incentive_percentage = $db->escapeString(($_POST['incentive_percentage']));
            $weekly_target = $db->escapeString(($_POST['weekly_target']));
            $staff_role_id = $db->escapeString(($_POST['staff_role_id']));
            $mobile = $db->escapeString(($_POST['mobile']));
            $salary_date = $db->escapeString(($_POST['salary_date']));
            $error = array();

            if (!empty($join_date) && !empty($branch_id) && !empty($salary)&& !empty($weekly_target)) {
                $sql_query = "UPDATE staffs SET  name='$name', salary_date='$salary_date',staff_role_id='$staff_role_id',status='$status', branch_id='$branch_id', join_date='$join_date',balance='$balance',sa_balance='$sa_balance',weekly_target = '$weekly_target',salary='$salary',incentive_percentage = $incentive_percentage,mobile = '$mobile' WHERE id =  $ID";
                $db->sql($sql_query);
                $update_result = $db->getResult();
                if (!empty($update_result)) {
                    $update_result = 0;
                } else {
                    $update_result = 1;
                }
        
                // check update result
                if ($update_result == 1) {
                    $error['update_staff'] = " <section class='content-header'><span class='label label-success'>staff updated Successfully</span></section>";
                } else {
                    $error['update_staff'] = " <span class='label label-danger'>Failed update users</span>";
                }

            }else{
                $error['update_staff'] = " <span class='label label-danger'> All Fields Required!</span>";
            }

}



// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM staffs WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();
if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "staffs.php";
    </script>
<?php } ?>
<section class="content-header">
    <h1>
        Edit Staff<small><a href='staffs.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Staffs</a></small></h1>
    <small><?php echo isset($error['update_staff']) ? $error['update_staff'] : ''; ?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <!-- Main row -->

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form id="edit_user_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="exampleInputEmail1">Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">E-mail</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="email" value="<?php echo $res[0]['email']; ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Mobile Number</label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="mobile" value="<?php echo $res[0]['mobile']; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Password</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="password" value="<?php echo $res[0]['password']; ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Salary Date</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="salary_date" value="<?php echo $res[0]['salary_date']; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Salary</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="salary" value="<?php echo $res[0]['salary']; ?>" >
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Date of Birth</label><i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="dob" value="<?php echo $res[0]['dob']; ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Family Mobile</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="family1" value="<?php echo $res[0]['family1']; ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Family Mobile Number</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="family2" value="<?php echo $res[0]['family2']; ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Bank Name</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="bank_name" value="<?php echo $res[0]['bank_name']; ?>" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Branch</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="branch" value="<?php echo $res[0]['branch']; ?>" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Account Number</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="bank_account_number" value="<?php echo $res[0]['bank_account_number']; ?>" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">IFSC Code</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="ifsc_code" value="<?php echo $res[0]['ifsc_code']; ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class='col-md-6'>
                                    <label for="exampleInputFile">Photo</label>
                                    <input type="file" accept="image/png,  image/jpeg"  name="image1" id="image1">
                                    <p class="help-block"><img id="blan" src="<?php echo $res[0]['photo']; ?>" style="max-width:50%;padding:4px;" /></p>
                            </div>
                            <div class='col-md-6'>
                                <label for="exampleInputFile">Resume</label>
                                <input type="file" accept="application/pdf" name="pdf1" id="pdf1">
                                <?php if(!empty($res[0]['resume'])) { ?>
                                    <p class="help-block"><iframe src="<?php echo $res[0]['resume']; ?>" style="width:100%;height:200px;"></iframe></p>
                                <?php } ?>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class='col-md-6'>
                                <label for="exampleInputFile">Aadhar Card</label>
                                <input type="file" accept="application/pdf" name="pdf1" id="pdf1">
                                <?php if(!empty($res[0]['aadhar_card'])) { ?>
                                    <p class="help-block"><iframe src="<?php echo $res[0]['aadhar_card']; ?>" style="width:100%;height:200px;"></iframe></p>
                                <?php } ?>
                            </div>
                            <div class='col-md-6'>
                                <label for="exampleInputFile">Education Certificate</label>
                                <input type="file" accept="application/pdf" name="pdf1" id="pdf1">
                                <?php if(!empty($res[0]['education_certificate'])) { ?>
                                    <p class="help-block"><iframe src="<?php echo $res[0]['education_certificate']; ?>" style="width:100%;height:200px;"></iframe></p>
                                <?php } ?>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Select Branch</label> <i class="text-danger asterik">*</i>
                                    <select id='branch_id' name="branch_id" class='form-control'>
                                           <option value="">--Select--</option>
                                                <?php
                                                $sql = "SELECT id,short_code FROM `branches`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['branch_id'] ? 'selected="selected"' : '';?>><?= $value['short_code'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
                            </div>
                            <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Select Role</label> <i class="text-danger asterik">*</i>
                                    <select id='staff_role_id' name="staff_role_id" class='form-control'>
                                           <option value="">--Select--</option>
                                                <?php
                                                $sql = "SELECT id,role FROM `staff_roles`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['staff_role_id'] ? 'selected="selected"' : '';?>><?= $value['role'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1">Join Date</label><i class="text-danger asterik">*</i>
                                <input type="date" class="form-control" name="join_date" value="<?php echo $res[0]['join_date']; ?>">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-3'>
                                    <label for="exampleInputEmail1">Balance</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="balance" value="<?php echo $res[0]['balance']; ?>" readonly>
                                </div>
                                <div class='col-md-3'>
                                    <label for="exampleInputEmail1">Incentive percentage</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="incentive_percentage" value="<?php echo $res[0]['incentive_percentage']; ?>">
                                </div>
                                <div class='col-md-3'>
                                    <label for="exampleInputEmail1">Salary Advance Balance</label>
                                    <input type="text" class="form-control" name="sa_balance" value="<?php echo $res[0]['sa_balance']; ?>" readonly>
                                </div>
                                <div class='col-md-3'>
                                    <label for="exampleInputEmail1">Weekly Target</label>
                                    <input type="number" class="form-control" name="weekly_target" value="<?php echo $res[0]['weekly_target']; ?>">
                                </div>
                        </div>
                            </div>
                        <br>
                        <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="control-label">Status</label><i class="text-danger asterik">*</i><br>
                                    <div id="status" class="btn-group">
                                        <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="0" <?= ($res[0]['status'] == 0) ? 'checked' : ''; ?>> Not-verified
                                        </label>
                                        <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="1" <?= ($res[0]['status'] == 1) ? 'checked' : ''; ?>> Verified
                                        </label>
                                        <label class="btn btn-danger" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="2" <?= ($res[0]['status'] == 2) ? 'checked' : ''; ?>> Cancelled
                                        </label>
                                    </div>
                                </div>
                        </div>

                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>

                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>
