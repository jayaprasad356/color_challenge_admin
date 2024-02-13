<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    $mobile = $db->escapeString(($_POST['mobile']));
    $referred_by = $db->escapeString($_POST['referred_by']);
    $refer_bonus = $db->escapeString($_POST['refer_bonus']);
    $basic = $db->escapeString($_POST['basic']);
    $lifetime = $db->escapeString($_POST['lifetime']);
    $premium = $db->escapeString($_POST['premium']);
    $basic_joined_date = $db->escapeString($_POST['basic_joined_date']);
    $lifetime_joined_date = $db->escapeString($_POST['lifetime_joined_date']);
    $premium_joined_date = $db->escapeString($_POST['premium_joined_date']);
    $error = array();

    if (empty($mobile)) {
        $error['mobile'] = " <span class='label label-danger'>Required!</span>";
    }

    if ($basic == 1 && empty($basic_joined_date)) {
        $error['basic_joined_date'] = " <span class='label label-danger'>Basic joined date is required!</span>";
    }

    if ($lifetime == 1 && empty($lifetime_joined_date)) {
        $error['lifetime_joined_date'] = " <span class='label label-danger'>Lifetime joined date is required!</span>";
    }

    if ($premium == 1 && empty($premium_joined_date)) {
        $error['premium_joined_date'] = " <span class='label label-danger'>Premium joined date is required!</span>";
    }

    if (!empty($referred_by)) {
        $referred_by = $db->escapeString($_POST['referred_by']);
        $sql = "SELECT id, status FROM users WHERE refer_code='$referred_by'";
        $db->sql($sql);
        $ares = $db->getResult();
        $num = $db->numRows($ares);
        if ($num == 0) {
            $error['referred_by'] = " <span class='label label-danger'>Invalid Referred By</span>";
        } else {
            $user_status = $ares[0]['status'];
            if ($user_status == 0) {
                $error['referred_by'] = " <span class='label label-danger'>Referred By is Unverified</span>";
            }
        }
    }

    if (!empty($mobile) &&
        (($basic != 1 || !empty($basic_joined_date)) &&
            ($lifetime != 1 || !empty($lifetime_joined_date)) &&
            ($premium != 1 || !empty($premium_joined_date)))) {

        $sql_query = "INSERT INTO approvals (mobile,referred_by,refer_bonus,basic,lifetime,premium,basic_joined_date,lifetime_joined_date,premium_joined_date) VALUES ('$mobile','$referred_by','$refer_bonus','$basic','$lifetime','$premium','$basic_joined_date','$lifetime_joined_date','$premium_joined_date')";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }

        if ($result == 1) {
            $error['add_jobs'] = "<section class='content-header'><span class='label label-success'>Approvals Added Successfully</span></section>";
        } else {
            $error['add_jobs'] = " <span class='label label-danger'>Failed</span>";
        }
    }
}
?>

<section class="content-header">
    <h1>Add New Approvals <small><a href='approvals.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Approvals</a></small></h1>

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
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Mobile</label><i class="text-danger asterik">*</i><?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>
                                    <input type="number" class="form-control" name="mobile" id="mobile" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Referred By</label><i class="text-danger asterik">*</i><?php echo isset($error['referred_by']) ? $error['referred_by'] : ''; ?>
                                    <input type="text" class="form-control" name="referred_by" id="referred_by" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Refer Bonus</label><i class="text-danger asterik">*</i><?php echo isset($error['refer_bonus']) ? $error['refer_bonus'] : ''; ?>
                                    <input type="text" class="form-control" name="refer_bonus" id="refer_bonus" required>
                                </div>
                                </div>
                             </div>
                             <br>
                         <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="">Basic</label><br>
                                    <input type="checkbox" id="basic_button" class="js-switch" <?= isset($res[0]['basic']) && $res[0]['basic'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="basic" name="basic">
                                 </div>
                             </div>
                             <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="">LifeTime</label><br>
                                    <input type="checkbox" id="lifetime_button" class="js-switch" <?= isset($res[0]['lifetime']) && $res[0]['lifetime'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="lifetime" name="lifetime">
                                 </div>
                             </div>
                             <div class="col-md-4">
                                  <div class="form-group">
                                    <label for="">Premium</label><br>
                                    <input type="checkbox" id="premium_button" class="js-switch" <?= isset($res[0]['premium']) && $res[0]['premium'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="premium" name="premium">
                                 </div>
                             </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Basic Joined Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['basic_joined_date']) ? $error['basic_joined_date'] : ''; ?>
                                    <input type="date" class="form-control" name="basic_joined_date" value="<?php echo $res[0]['basic_joined_date']; ?>">
                                </div>
                                    <div class="col-md-4">
                                    <label for="exampleInputEmail1">LifeTime Joined Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['lifetime_joined_date']) ? $error['lifetime_joined_date'] : ''; ?>
                                    <input type="date" class="form-control" name="lifetime_joined_date" value="<?php echo $res[0]['lifetime_joined_date']; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1">Premium Joined Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['premium_joined_date']) ? $error['premium_joined_date'] : ''; ?>
                                    <input type="date" class="form-control" name="premium_joined_date" value="<?php echo $res[0]['premium_joined_date']; ?>">
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

<script>
    var changeCheckbox = document.querySelector('#basic_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#basic').val(1);

        } else {
            $('#basic').val(0);
        }
    };
</script>
<script>
    var changeCheckbox = document.querySelector('#lifetime_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#lifetime').val(1);

        } else {
            $('#lifetime').val(0);
        }
    };
</script>
<script>
    var changeCheckbox = document.querySelector('#premium_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#premium').val(1);

        } else {
            $('#premium').val(0);
        }
    };
</script>
<?php $db->disconnect(); ?>
