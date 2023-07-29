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
    $date = date('Y-m-d');
    $mobile = $db->escapeString($_POST['mobile']);
    $upi = $db->escapeString($_POST['upi']);
    $earn = $db->escapeString($_POST['earn']);
    $balance = $db->escapeString($_POST['balance']);
    $referred_by = $db->escapeString($_POST['referred_by']);
    $refer_code= $db->escapeString($_POST['refer_code']);
    $withdrawal_status = $db->escapeString($_POST['withdrawal_status']);
    $challenge_status = $db->escapeString($_POST['challenge_status']);
    $generate_coin = $db->escapeString($_POST['generate_coin']);
    $status = $db->escapeString($_POST['status']);
    $level = $db->escapeString($_POST['level']);
    $total_coins = $db->escapeString($_POST['total_coins']);
    $joined_date = '';

    if($generate_coin == 1){
        $joined_date = (isset($_POST['joined_date']) && !empty($_POST['joined_date'])) ? $db->escapeString($_POST['joined_date']) : $date;


    }
    
    $error = array();

    if (empty($mobile)) {
        $error['mobile'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($upi)) {
        $error['upi'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($balance)) {
        $error['balance'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($earn)) {
        $error['earn'] = " <span class='label label-danger'>Required!</span>";
    }
    
    $sa_refer_count=$res[0]['sa_refer_count'];
                $refer_sa_balance=200;
            
              
                $sql_query = "UPDATE users SET `l_referral_count` = l_referral_count + 1,`earn` = earn + $referral_bonus,`balance` = balance + $referral_bonus,`salary_advance_balance`=salary_advance_balance +$refer_sa_balance,`sa_refer_count`=sa_refer_count + 1 WHERE id =  $user_id";
                $db->sql($sql_query);
                $fn->update_refer_code_cost($user_id);
                $sql_query = "INSERT INTO transactions (user_id,amount,datetime,type)VALUES($user_id,$referral_bonus,'$datetime','refer_bonus')";
                $db->sql($sql_query);
                $sql_query = "INSERT INTO salary_advance_trans (user_id,refer_user_id,amount,datetime,type)VALUES($ID,$user_id,'$refer_sa_balance','$datetime','credit')";
                $db->sql($sql_query);
                if($ref_user_status == 1 && ($ref_code_generate == 1 || $ref_code_generate == 0 && $ref_worked_days < $ref_duration)  ){

                    $ref_per_code_cost = $fn->get_code_per_cost($user_id);


                    $amount = $refer_bonus_codes  * $ref_per_code_cost;
                    $sql_query = "UPDATE users SET `earn` = earn + $amount,`balance` = balance + $amount,`today_codes` = today_codes + $refer_bonus_codes,`total_codes` = total_codes + $refer_bonus_codes WHERE refer_code =  '$referred_by' AND status = 1";
                    $db->sql($sql_query);
                    $sql_query = "INSERT INTO transactions (user_id,amount,codes,datetime,type)VALUES($user_id,$amount,$refer_bonus_codes,'$datetime','code_bonus')";
                    $db->sql($sql_query);
                }
                $sql_query = "UPDATE users SET refer_bonus_sent = 1 WHERE id =  $ID";
                $db->sql($sql_query);

            

    if (!empty($mobile)) {
        $sql_query = "UPDATE users SET mobile='$mobile',upi='$upi',earn='$earn',balance='$balance',referred_by='$referred_by',refer_code='$refer_code',withdrawal_status='$withdrawal_status',challenge_status='$challenge_status',generate_coin='$generate_coin',status='$status',level = $level,joined_date = '$joined_date',total_coins = $total_coins WHERE id = $ID";
        $db->sql($sql_query);
        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        // check update result
        if ($update_result == 1) {
            $error['update_user'] = " <section class='content-header'><span class='label label-success'>User Details updated Successfully</span></section>";
        } else {
            $error['update_user'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT *, DATE_FORMAT(joined_date, '%Y-%m-%d') AS joined_date FROM users WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();

$sql_query = "SELECT * FROM users WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "users.php";
    </script>
<?php } ?>
<section class="content-header">
    <h1>
        Edit Users<small><a href='users.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to users</a></small></h1>
    <small><?php echo isset($error['update_user']) ? $error['update_user'] : ''; ?></small>
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
                           <div class="form-group col-md-3">
                                <h4 class="box-title"> </h4>
                                <a class="btn btn-block btn-primary" href="add-coins.php?id=<?php echo $ID ?>"><i class="fa fa-plus-square"></i> Add Coins</a>
                            </div>
                             <div class="form-group col-md-3">
                                <h4 class="box-title"> </h4>
                                <a class="btn btn-block btn-success" href="add-balance.php?id=<?php echo $ID ?>"><i class="fa fa-plus-square"></i>  Add Balance</a>
                            </div> 
                </div>
                <!-- /.box-header -->
                <form id="edit_project_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                              
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"> Mobile Number</label> <i class="text-danger asterik">*</i><?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>
                                    <input type="text" class="form-control" name="mobile" value="<?php echo $res[0]['mobile']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"> UPI</label> <i class="text-danger asterik">*</i><?php echo isset($error['upi']) ? $error['upi'] : ''; ?>
                                    <input type="text" class="form-control" name="upi" value="<?php echo $res[0]['upi']; ?>">
                                </div>

                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                            <div class="col-md-6">
                                    <label for="exampleInputEmail1"> Refered By</label> <i class="text-danger asterik">*</i><?php echo isset($error['referred_by']) ? $error['referred_by'] : ''; ?>
                                    <input type="text" class="form-control" name="referred_by" value="<?php echo $res[0]['referred_by']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"> Refer Code</label> <i class="text-danger asterik">*</i><?php echo isset($error['refer_code']) ? $error['refer_code'] : ''; ?>
                                    <input type="text" class="form-control" name="refer_code" value="<?php echo $res[0]['refer_code']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                            <div class="col-md-6">
                                    <label for="exampleInputEmail1">Earn</label> <i class="text-danger asterik">*</i><?php echo isset($error['earn']) ? $error['earn'] : ''; ?>
                                    <input type="text" class="form-control" name="earn" value="<?php echo $res[0]['earn']; ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"> Balance</label> <i class="text-danger asterik">*</i><?php echo isset($error['balance']) ? $error['balance'] : ''; ?>
                                    <input type="text" class="form-control" name="balance" value="<?php echo $res[0]['balance']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Generate Coin</label><br>
                                    <input type="checkbox" id="generate_button" class="js-switch" <?= isset($res[0]['generate_coin']) && $res[0]['generate_coin'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="generate_coin" name="generate_coin" value="<?= isset($res[0]['generate_coin']) && $res[0]['generate_coin'] == 1 ? 1 : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                    <label for="exampleInputEmail1"> Level</label> <i class="text-danger asterik">*</i><?php echo isset($error['level']) ? $error['level'] : ''; ?>
                                    <input type="text" class="form-control" name="level" value="<?php echo $res[0]['level']; ?>">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                        <div class="col-md-4">
                                    <label for="exampleInputEmail1"> Total Coins</label> <i class="text-danger asterik">*</i><?php echo isset($error['total_coins']) ? $error['level'] : ''; ?>
                                    <input type="text" class="form-control" name="total_coins" value="<?php echo $res[0]['total_coins']; ?>">
                            </div>
                        <div class="col-md-4">
                                    <label for="exampleInputEmail1">Joined Date</label><i class="text-danger asterik">*</i>
                                    <input type="date" class="form-control" name="joined_date" value="<?php echo $res[0]['joined_date']; ?>">
                                </div>

						</div>
                        <div class="row">
                                <div class="form-group col-md-8">
                                    <label class="control-label">Status</label><i class="text-danger asterik">*</i><br>
                                    <div id="status" class="btn-group">
                                        <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="1" <?= ($res[0]['status'] == 1) ? 'checked' : ''; ?>> Active
                                        </label>
                                        <label class="btn btn-danger" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="0" <?= ($res[0]['status'] == 0) ? 'checked' : ''; ?>> Blocked
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
    var changeCheckbox = document.querySelector('#generate_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#generate_coin').val(1);

        } else {
            $('#generate_coin').val(0);
        }
    };
</script>