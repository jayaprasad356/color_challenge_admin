<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnUpdate'])) {

    $register_coins = $db->escapeString(($_POST['register_coins']));
    $refer_coins = $db->escapeString(($_POST['refer_coins']));
    $withdrawal_status = $db->escapeString(($_POST['withdrawal_status']));
    $min_dp_coins = $db->escapeString(($_POST['min_dp_coins']));
    $max_dp_coins = $db->escapeString(($_POST['max_dp_coins']));
    $challenge_status = $db->escapeString(($_POST['challenge_status']));
    $min_withdrawal = $db->escapeString(($_POST['min_withdrawal']));
    $upi = $db->escapeString(($_POST['upi']));
    $contact_us = $db->escapeString(($_POST['contact_us']));
    $error = array();
    $sql_query = "UPDATE settings SET register_coins=$register_coins,refer_coins='$refer_coins',withdrawal_status=$withdrawal_status,challenge_status=$challenge_status,min_dp_coins='$min_dp_coins',max_dp_coins='$max_dp_coins',min_withdrawal ='$min_withdrawal',upi='$upi',contact_us='$contact_us' WHERE id=1";
    $db->sql($sql_query);
    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }

    if ($result == 1) {
        
        $error['update'] = "<section class='content-header'>
                                        <span class='label label-success'>Settings Updated Successfully</span> </section>";
    } else {
        $error['update'] = " <span class='label label-danger'>Failed</span>";
    }
    }

    // create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM settings WHERE id = 1";
$db->sql($sql_query);
$res = $db->getResult();
?>
<section class="content-header">
    <h1>Settings</h1>
    <?php echo isset($error['update']) ? $error['update'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="delivery_charge" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                            <div class="row">
							    <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Withdrawal Status</label><br>
                                        <input type="checkbox" id="withdrawal_button" class="js-switch" <?= isset($res[0]['withdrawal_status']) && $res[0]['withdrawal_status'] == 1 ? 'checked' : '' ?>>
                                        <input type="hidden" id="withdrawal_status" name="withdrawal_status" value="<?= isset($res[0]['withdrawal_status']) && $res[0]['withdrawal_status'] == 1 ? 1 : 0 ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Challenge Status</label><br>
                                        <input type="checkbox" id="challenge_button" class="js-switch" <?= isset($res[0]['challenge_status']) && $res[0]['challenge_status'] == 1 ? 'checked' : '' ?>>
                                        <input type="hidden" id="challenge_status" name="challenge_status" value="<?= isset($res[0]['challenge_status']) && $res[0]['challenge_status'] == 1 ? 1 : 0 ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">UPI</label><br>
                                        <input type="text" class="form-control" name="upi" value="<?= $res[0]['upi'] ?>">
                                    </div>
                                </div>
								
                            </div>
                           <br>
						   <div class="row">
						        <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Register Coins</label><br>
                                        <input type="number"class="form-control" name="register_coins" value="<?= $res[0]['register_coins'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Refer Coins</label><br>
                                        <input type="number"class="form-control" name="refer_coins" value="<?= $res[0]['refer_coins'] ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Minimum Withdrawal</label><br>
                                        <input type="number"class="form-control" name="min_withdrawal" value="<?= $res[0]['min_withdrawal'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Minimum Deposit Coins</label><br>
                                        <input type="number"class="form-control" name="min_dp_coins" value="<?= $res[0]['min_dp_coins'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Maximum Deposit Coins</label><br>
                                        <input type="number"class="form-control" name="max_dp_coins" value="<?= $res[0]['max_dp_coins'] ?>">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Contact Us</label><br>
                                        <textarea type="text" rows="3" class="form-control" name="contact_us" ><?= $res[0]['contact_us'] ?></textarea>
                                    </div>
                                </div>
                            </div>
                    </div>
                  
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnUpdate">Update</button>
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>

<?php $db->disconnect(); ?>

<script>
    var changeCheckbox = document.querySelector('#challenge_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#challenge_status').val(1);

        } else {
            $('#challenge_status').val(0);
        }
    };
</script>

<script>
    var changeCheckbox = document.querySelector('#withdrawal_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#withdrawal_status').val(1);

        } else {
            $('#withdrawal_status').val(0);
        }
    };
</script>
