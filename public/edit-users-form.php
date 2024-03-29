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

    $datetime = date('Y-m-d H:i:s');
    $date = date('Y-m-d');
    $mobile = $db->escapeString($_POST['mobile']);

    $earn = $db->escapeString($_POST['earn']);
    $balance = $db->escapeString($_POST['balance']);
    $referred_by = $db->escapeString($_POST['referred_by']);
    $refer_code= $db->escapeString($_POST['refer_code']);
    $withdrawal_status = $db->escapeString($_POST['withdrawal_status']);
    $blocked = $db->escapeString($_POST['blocked']);
    $min_withdrawal = $db->escapeString($_POST['min_withdrawal']);
    $status = $db->escapeString($_POST['status']);
    
    $account_num = $db->escapeString(($_POST['account_num']));
    $holder_name = $db->escapeString(($_POST['holder_name']));
    $bank = $db->escapeString(($_POST['bank']));
    $branch = $db->escapeString(($_POST['branch']));
    $ifsc = $db->escapeString(($_POST['ifsc']));
    $device_id = $db->escapeString(($_POST['device_id']));
    $joined_date = $db->escapeString($_POST['joined_date']);
    $basic_wallet = $db->escapeString(($_POST['basic_wallet']));
    $premium_wallet = $db->escapeString(($_POST['premium_wallet']));
    $total_ads = $db->escapeString(($_POST['total_ads']));
    $today_ads = $db->escapeString($_POST['today_ads']);
    $lead_id = $db->escapeString(($_POST['lead_id']));
    $support_id = $db->escapeString(($_POST['support_id']));
    $branch_id = $db->escapeString(($_POST['branch_id']));
    $support_lan = $db->escapeString(($_POST['support_lan']));
    $gender = $db->escapeString(($_POST['gender']));
    $current_refers = $db->escapeString(($_POST['current_refers']));
    $target_refers = $db->escapeString(($_POST['target_refers']));
    $plan = $db->escapeString(($_POST['plan']));
    $plan_type = $db->escapeString(($_POST['plan_type']));
    $total_referrals = $db->escapeString(($_POST['total_referrals']));
    $ads_time = $db->escapeString(($_POST['ads_time']));
    $ads_cost = isset($_POST['ads_cost']) ? $db->escapeString($_POST['ads_cost']) : 0;
    $old_plan = $db->escapeString(($_POST['old_plan']));
    $worked_days = $db->escapeString(($_POST['worked_days']));
    $description = $db->escapeString(($_POST['description']));
    $age = $db->escapeString(($_POST['age']));
    $project_type = $db->escapeString(($_POST['project_type']));
    $performance = $db->escapeString(($_POST['performance']));
    $platform_type = $db->escapeString(($_POST['platform_type']));
    $missed_days = $db->escapeString(($_POST['missed_days']));
    $order_id = $db->escapeString(($_POST['order_id']));
    $payment_verified = $db->escapeString(($_POST['payment_verified']));
    $store_balance = $db->escapeString(($_POST['store_balance']));
    $city = $db->escapeString(($_POST['city']));
    $without_work = $db->escapeString(($_POST['without_work']));
    $max_withdrawal = $db->escapeString(($_POST['max_withdrawal']));
    $old_balance = $db->escapeString(($_POST['old_balance']));
    $pay_later = $db->escapeString(($_POST['pay_later']));
    $whatsapp_status = $db->escapeString(($_POST['whatsapp_status']));
    $basic = $db->escapeString(($_POST['basic']));
    $lifetime = $db->escapeString(($_POST['lifetime']));
    $premium = $db->escapeString(($_POST['premium']));
    $basic_days = $db->escapeString(($_POST['basic_days']));
    $lifetime_days = $db->escapeString(($_POST['lifetime_days']));
    $premium_days = $db->escapeString(($_POST['premium_days']));
    $basic_income = $db->escapeString(($_POST['basic_income']));
    $lifetime_income = $db->escapeString(($_POST['lifetime_income']));
    $premium_income = $db->escapeString(($_POST['premium_income']));
    $basic_joined_date = $db->escapeString(($_POST['basic_joined_date']));
    $lifetime_joined_date = $db->escapeString(($_POST['lifetime_joined_date']));
    $premium_joined_date = $db->escapeString(($_POST['premium_joined_date']));
    $aadhaar_num = $db->escapeString(($_POST['aadhaar_num']));
    $free_income = $db->escapeString(($_POST['free_income']));
    $today_earn = $db->escapeString(($_POST['today_earn']));
    $team_size = $db->escapeString(($_POST['team_size']));
    $valid_team = $db->escapeString(($_POST['valid_team']));
    $total_assets = $db->escapeString(($_POST['total_assets']));
    $level_income = $db->escapeString(($_POST['level_income']));
    $refer_level_income = $db->escapeString(($_POST['refer_level_income']));
    $total_income = $db->escapeString(($_POST['total_income']));
    $team_income = $db->escapeString(($_POST['team_income']));
    $today_income = $db->escapeString(($_POST['today_income']));
    $valid  = $db->escapeString(($_POST['valid']));
    $c_referred_by = $db->escapeString(($_POST['c_referred_by']));
    $d_referred_by = $db->escapeString(($_POST['d_referred_by']));

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
    if (empty($languages)) {
        $error['languages'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($lead_id)) {
        $error['update_users'] = " <span class='label label-danger'> Lead Required!</span>";
    }
    if (empty($support_id)) {
        $error['update_users'] = " <span class='label label-danger'> Support Required!</span>";
    }
    if (empty($branch_id)) {
        $error['update_users'] = " <span class='label label-danger'> Branch Required!</span>";
    }
    
    
    
            

    if (!empty($mobile) && !empty($lead_id)  && 
    !empty($support_id) && 
    !empty($branch_id)) {

        $refer_bonus_sent = $fn->get_value('users','refer_bonus_sent',$ID);
 
        if($status == 1 && !empty($referred_by) && $refer_bonus_sent != 1){
           
            
            $sql_query = "SELECT * FROM users WHERE refer_code =  '$referred_by'";
            $db->sql($sql_query);
            $res = $db->getResult();
            $num = $db->numRows($res);

            
            if ($num == 1 && $plan != 'A1W'){
                $user_status = $res[0]['status'];
                $user_id = $res[0]['id'];
                $join = '';
                if($user_status == 1){
                    $ads = 1200;
                    $referral_bonus = 150;
                    if($plan == 'A1' || $plan == 'A1U'){
                        // $sql_query = "UPDATE users SET `total_referrals` = total_referrals + 1,`earn` = earn + $referral_bonus,`balance` = balance + $referral_bonus ,`today_ads` = today_ads + $ads,`total_ads` = total_ads + $ads WHERE id =  $user_id";
                        // $db->sql($sql_query);
                        // $sql_query = "INSERT INTO transactions (user_id,amount,datetime,type,ads)VALUES($user_id,$referral_bonus,'$datetime','refer_bonus',$ads)";
                        // $db->sql($sql_query);

                    }
                    // else{
                    //     $sql_query = "UPDATE users SET `total_referrals` = total_referrals + 1,`earn` = earn + $referral_bonus,`balance` = balance + $referral_bonus , `today_ads` = today_ads + 10,`total_ads` = total_ads + 10  WHERE id =  $user_id";
                    //     $db->sql($sql_query);
                    //     $sql_query = "INSERT INTO transactions (user_id,amount,datetime,type)VALUES($user_id,$referral_bonus,'$datetime','refer_bonus')";
                    //     $db->sql($sql_query);

                    // }

                                        

    
                    $sql_query = "UPDATE users SET refer_bonus_sent = 1 WHERE id =  $ID";
                    $db->sql($sql_query);



                }
              
 
            }
            
        }
        $register_bonus_sent = $fn->get_value('users','register_bonus_sent',$ID);
            if ($status == 1 && $register_bonus_sent != 1 ) {
                $sql_query = "UPDATE users SET register_bonus_sent = 1 WHERE id =  $ID";
                $db->sql($sql_query);
        
                $joined_date = $date;
                $today_ads = 0;
                $total_ads = 0;
                $premium_wallet = 0;
                $current_refers = 0;
                $target_refers = 0;
                $store_balance = 0;
               

                if(strlen($referred_by) < 4){
                    $incentives = 50;
                }else{
                    $incentives = 7.5;
                    
                }

                // $sql_query = "UPDATE staffs SET incentives = incentives + $incentives,earn = earn + $incentives,balance = balance + $incentives,supports = supports + 1 WHERE id =  $support_id";
                // $db->sql($sql_query);
    
                // $sql_query = "UPDATE staffs SET incentives = incentives + $incentives,earn = earn + $incentives,balance = balance + $incentives,leads = leads + 1 WHERE id =  $lead_id";
                // $db->sql($sql_query);
                
                // $sql_query = "INSERT INTO incentives (user_id,staff_id,amount,datetime,type)VALUES($ID,$support_id,$incentives,'$datetime','support')";
                // $db->sql($sql_query);
    
                // $sql_query = "INSERT INTO incentives (user_id,staff_id,amount,datetime,type)VALUES($ID,$lead_id,$incentives,'$datetime','lead')";
                // $db->sql($sql_query);
    
                // $sql_query = "INSERT INTO staff_transactions (staff_id,amount,datetime,type)VALUES($support_id,$incentives,'$datetime','incentives')";
                // $db->sql($sql_query);
    
                // $sql_query = "INSERT INTO staff_transactions (staff_id,amount,datetime,type)VALUES($lead_id,$incentives,'$datetime','incentives')";
                // $db->sql($sql_query);

            }
            if($plan == 'A1'){

                $min_withdrawal = 20;
                $max_withdrawal = 20;
                $ads_time = 25;
                if($referred_by == 'free'){
                    $ads_cost = 0.10;

                }
            }elseif ($plan == 'A1S') {
                $min_withdrawal = 100;
                $ads_time = 12;
                
            }elseif ($plan == 'A2') {
                $min_withdrawal = 20;
                $max_withdrawal = 20;
            }

            if($plan == 'A1U' && $plan_type == 'new_plan'){
                $ads_cost = 0.125;
                $current_refers = 0;
                $target_refers = 0;
                $earn = 0;
                $joined_date = $date;
                $today_ads = 0;
                $total_ads = 0;
                $total_referrals = 0;
                $premium_wallet = 0;
                $old_plan = 0;
                $missed_days = 0;
                $balance = 0;
                $worked_days = 0;
                $store_balance = 0;

                $min_withdrawal = 45;
                $ads_time = 15;
            }
            if($plan == 'A1U'){
                $ads_cost = 0.125;

                $min_withdrawal = 45;
                $max_withdrawal = 300;
                $ads_time = 15;
            }

            if($plan == 'A1U' && $without_work == 1){
                $ads_cost = 0.125;
                $min_withdrawal = 100;
                $max_withdrawal = 300;
                $ads_time = 15;
            }

            if($plan == 'A1W'){

                $min_withdrawal = 15;
                $ads_time = 25;
            }


            if(($plan == 'A1' || $plan == 'A1W') && $plan_type == 'new_plan'){
                $ads_cost = 0.125;
                $current_refers = 0;
                $target_refers = 0;
                $earn = 0;
                $joined_date = $date;
                $today_ads = 0;
                $total_ads = 0;
                $total_referrals = 0;
                $premium_wallet = 0;
                $old_plan = 0;
                $ads_time = 25;
                $missed_days = 0;
                $balance = 0;
                $worked_days = 0;
                $store_balance = 0;
                if($plan == 'A1W'){
                    $min_withdrawal = 15;
                    $ads_time = 25;

                }


            }
            if($plan_type == 'free'){
                $earn = 0;
                $worked_days = 0;
                $ads_cost = 0.10;
                $joined_date = $date;
                $today_ads = 0;
                $total_ads = 0;
                $total_referrals = 0;
                $old_plan = 0;
                $ads_time = 25;
                $project_type = 'free';
                $missed_days = 0;
                $store_balance = 0;
            }
            if ($status == 0) {
                $min_withdrawal = 12;
            }
            if ($basic == '0' && $lifetime == '0' && $premium == '0' && $status == 1 && $without_work == 0) {
                $error['update_users'] = "<section class='content-header'><span class='label label-danger'>Choose Any plan</span></section>";
            } else {
                if ($basic == '1' && empty($basic_joined_date)) {
                    $error['update_users'] = "<section class='content-header'><span class='label label-danger'>Please provide basic joined date</span></section>";
                } elseif ($lifetime == '1' && empty($lifetime_joined_date)) {
                    $error['update_users'] = "<section class='content-header'><span class='label label-danger'>Please provide lifetime joined date</span></section>";
                } elseif ($premium == '1' && empty($premium_joined_date)) {
                    $error['update_users'] = "<section class='content-header'><span class='label label-danger'>Please provide premium joined date</span></section>";
                } else {
            
            $sql_query = "UPDATE users SET mobile='$mobile',earn='$earn',balance='$balance',referred_by='$referred_by',refer_code='$refer_code',withdrawal_status='$withdrawal_status',min_withdrawal='$min_withdrawal',joined_date = '$joined_date',account_num='$account_num', holder_name='$holder_name', bank='$bank', branch='$branch', ifsc='$ifsc', device_id='$device_id', basic_wallet='$basic_wallet', premium_wallet='$premium_wallet', total_ads = $total_ads, today_ads = $today_ads,status=$status,lead_id='$lead_id',support_id='$support_id',branch_id='$branch_id',support_lan='$support_lan',gender='$gender',current_refers='$current_refers',target_refers='$target_refers',plan = '$plan',total_referrals = $total_referrals,ads_time='$ads_time',ads_cost='$ads_cost',old_plan = '$old_plan',worked_days = '$worked_days',blocked = '$blocked',description = '$description',age = '$age',project_type = '$project_type',performance = '$performance',platform_type = '$platform_type',missed_days='$missed_days',payment_verified = '$payment_verified',order_id='$order_id',store_balance='$store_balance',city='$city',without_work='$without_work',max_withdrawal = '$max_withdrawal',old_balance = '$old_balance',pay_later = $pay_later,whatsapp_status = '$whatsapp_status',basic = '$basic', lifetime = '$lifetime',premium = '$premium',basic_days = '$basic_days', lifetime_days = '$lifetime_days', premium_days = '$premium_days',basic_income = '$basic_income' ,lifetime_income = '$lifetime_income',premium_income = '$premium_income',basic_joined_date = '$basic_joined_date',lifetime_joined_date = '$lifetime_joined_date',premium_joined_date = '$premium_joined_date' ,aadhaar_num = '$aadhaar_num' ,free_income = '$free_income',today_earn = '$today_earn' ,team_size = '$team_size',valid_team = '$valid_team',total_assets = '$total_assets',level_income = '$level_income',refer_level_income = '$refer_level_income',total_income = '$total_income',team_income = '$team_income',today_income = '$today_income',valid  = '$valid',c_referred_by  = '$c_referred_by',d_referred_by  = '$d_referred_by' WHERE id = $ID";
            $db->sql($sql_query);
            $update_result = $db->getResult();
    
            if (!empty($update_result)) {
                $update_result = 0;
            } else {
                $update_result = 1;
            }
    
            // check update result
            if ($update_result == 1) {
                $error['update_users'] = " <section class='content-header'><span class='label label-success'>User Details updated Successfully</span></section>";
            } else {
                $error['update_users'] = " <span class='label label-danger'>Failed to update</span>";
            }
        }
    }
}
}

 
$data = array();

if (!empty($aadhaar_num)) {
    $sql_query = "SELECT * FROM users WHERE aadhaar_num = '$aadhaar_num' AND id != $ID";
    $db->sql($sql_query);
    $aadhaar_result = $db->getResult();
    if (!empty($aadhaar_result)) {
        $error['update_users'] = "<span class='label label-danger'>Unique ID already exists</span>";
    }
}
$sql_query = "SELECT *, DATE_FORMAT(joined_date, '%Y-%m-%d') AS joined_date FROM users WHERE id = $ID";
$db->sql($sql_query);
$res = $db->getResult();

$sql_query = "SELECT * FROM users WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

$refer_code = $res[0]['refer_code'];
$referred_by = isset($_POST['referred_by']) ? $_POST['referred_by'] : $res[0]['referred_by'];

$refer_name = '';
$refer_mobile = '';

if (!empty($referred_by)) {
    $sql_query = "SELECT name, mobile FROM users WHERE refer_code = '$referred_by'";
    $db->sql($sql_query);
    $result = $db->getResult();
    if (!empty($result)) {
        $refer_name = isset($result[0]['name']) ? $result[0]['name'] : '';
        $refer_mobile = isset($result[0]['mobile']) ? $result[0]['mobile'] : '';
    }
}

if (isset($_POST['btncheck'])) {

    $refer_code = $res[0]['refer_code'];
    $referred_by = isset($_POST['referred_by']) ? $_POST['referred_by'] : $res[0]['referred_by'];
    
    $refer_name = '';
    $refer_mobile = '';
    
    if (!empty($referred_by)) {
        $sql_query = "SELECT name, mobile FROM users WHERE refer_code = '$referred_by'";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $refer_name = isset($result[0]['name']) ? $result[0]['name'] : '';
            $refer_mobile = isset($result[0]['mobile']) ? $result[0]['mobile'] : '';
        }
    }
}

if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "users.php";
    </script>
<?php } ?>
<section class="content-header">
    <h1>
        Edit Users<small><a href='users.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to users</a></small></h1>
    <small><?php echo isset($error['update_users']) ? $error['update_users'] : ''; ?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <!-- Main row -->

    <div class="row">
        <div class="col-md-11">

            <!-- general form elements -->
            <div class="box box-primary">
               <div class="box-header with-border">
                           <div class="form-group col-md-3">
                                <h4 class="box-title"> </h4>
                                <a class="btn btn-block btn-primary" href="add_refer_bonus.php?id=<?php echo $ID ?>"><i class="fa fa-plus-square"></i> Add Refer Bonus</a>
                            </div>
                             <div class="form-group col-md-3">
                                <h4 class="box-title"> </h4>
                                <a class="btn btn-block btn-success" href="add-balance.php?id=<?php echo $ID ?>"><i class="fa fa-plus-square"></i>  Add Balance</a>
                            </div> 
                </div>
                <!-- /.box-header -->
                <form id="edit_project_form" method="post" enctype="multipart/form-data">
                <input type="hidden" class="form-control" name="total_referrals" value="<?php echo $res[0]['total_referrals']; ?>">
                <div class="box-body">
                        <div class="row">
                              <div class="form-group">
                              <div class="col-md-3">
                                    <label for="exampleInputEmail1">Name</label> <i class="text-danger asterik">*</i<?php echo isset($error['name']) ? $error['name'] : ''; ?>>
                                     <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']; ?>">
                                  </div>
                                 <div class="col-md-3">
                                    <label for="exampleInputEmail1"> Mobile Number</label> <i class="text-danger asterik">*</i<?php echo isset($error['mobile']) ? $error['mobile'] : ''; ?>>
                                     <input type="text" class="form-control" name="mobile" value="<?php echo $res[0]['mobile']; ?>">
                                  </div>
                               <div class="col-md-3">
                                    <label for="exampleInputEmail1"> Refered By</label> <i class="text-danger asterik">*</i<?php echo isset($error['referred_by']) ? $error['referred_by'] : ''; ?>>
                                    <input type="text" class="form-control" name="referred_by" value="<?php echo $res[0]['referred_by']; ?>">
                                 </div>  
                               <div class="col-md-2">
                                    <label for="exampleInputEmail1">Check Button</label><i class="text-danger asterisk">*</i>
                                    <button type="submit" class="btn btn-danger"  name="btncheck">Check</button>
                                  </div>
                               </div>
                             </div>
                          <br>
                          <div class="row">
                              <div class="form-group">
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1"> Refer Name</label><i class="text-danger asterisk">*</i>
                                    <input type="text" class="form-control" name="refer_name" value="<?php echo $refer_name; ?>" readonly>
                                 </div>
                               <div class="col-md-3">
                                    <label for="exampleInputEmail1"> Refer Mobile</label><i class="text-danger asterisk">*</i>
                                    <input type="text" class="form-control" name="refer_mobile" value="<?php echo $refer_mobile; ?>" readonly>
                                  </div>
                                  <div class="col-md-5">
                                            <label for="exampleInputEmail1">Description</label> <i class="text-danger asterik">*</i><?php echo isset($error['description']) ? $error['description'] : ''; ?>
                                            <textarea  type="text" rows="3" class="form-control" name="description"><?php echo $res[0]['description']?></textarea>
                                    </div>
                               </div>
                             </div>
                          <br>
                        <div class="row">
                            <div class="form-group">
                            <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Plan</label> <i class="text-danger asterik">*</i>
                                    <select id='plan' name="plan" class='form-control'>
                                     <option value='A1' <?php if ($res[0]['plan'] == 'A1') echo 'selected'; ?>>A1</option>
                                      <option value='A2' <?php if ($res[0]['plan'] == 'A2') echo 'selected'; ?>>A2</option>
                                      <option value='A1S' <?php if ($res[0]['plan'] == 'A1S') echo 'selected'; ?>>A1S</option>
                                      <option value='A1U' <?php if ($res[0]['plan'] == 'A1U') echo 'selected'; ?>>A1U</option>
                                      <option value='A1W' <?php if ($res[0]['plan'] == 'A1W') echo 'selected'; ?>>A1W</option>
                                    </select>
                            </div>

                                <div class="col-md-4">
                                    <label for="exampleInputEmail1"> Refer Code</label> <i class="text-danger asterik">*</i><?php echo isset($error['refer_code']) ? $error['refer_code'] : ''; ?>
                                    <input type="text" class="form-control" name="refer_code" value="<?php echo $res[0]['refer_code']; ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Select Lead</label> <i class="text-danger asterik">*</i>
                                    <select id='lead_id' name="lead_id" class='form-control' style="background-color: #7EC8E3">
                                           <option value="">--Select--</option>
                                                <?php
                                                $sql = "SELECT * FROM `staffs`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['lead_id'] ? 'selected="selected"' : '';?>><?= $value['name'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
                            </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Select Support</label> <i class="text-danger asterik">*</i>
                                    <select id='support_id' name="support_id" class='form-control' style="background-color: #7EC8E3">
                                             <option value="">--Select--</option>
                                                <?php
                                                $sql = "SELECT * FROM `staffs`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['support_id'] ? 'selected="selected"' : '';?>><?= $value['name'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
                            </div>
                            <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Select Branch</label> <i class="text-danger asterik">*</i>
                                    <select id='branch_id' name="branch_id" class='form-control'>
                                           <option value="">--Select--</option>
                                                <?php
                                                $sql = "SELECT * FROM `branches`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['branch_id'] ? 'selected="selected"' : '';?>><?= $value['name'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
                            </div>
                            <div class="form-group col-md-4">
                                    <label class="control-label">Status</label><i class="text-danger asterik">*</i><br>
                                    <div id="status" class="btn-group">
                                        <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="0" <?= ($res[0]['status'] == 0) ? 'checked' : ''; ?>> Not-verified
                                        </label>
                                        <label class="btn btn-success" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="1" <?= ($res[0]['status'] == 1) ? 'checked' : ''; ?>> Verified
                                        </label>
                                        <label class="btn btn-danger" data-toggle-class="btn-default" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="2" <?= ($res[0]['status'] == 2) ? 'checked' : ''; ?>> Blocked
                                        </label>
                                    </div>
                                </div>
                        </div>
                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>
                    </div>
                    <hr>
                    <br>
                        <div class="row">
                            <div class="form-group">
                            <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Account Number</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="account_num" value="<?php echo $res[0]['account_num']; ?>">
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputEmail1">Holder Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="holder_name" value="<?php echo $res[0]['holder_name']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                            <div class="col-md-4">
                                    <label for="exampleInputEmail1">IFSC</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="ifsc" value="<?php echo $res[0]['ifsc']; ?>">
                                </div>
                                <div class="col-md-4">
                                <label for="exampleInputEmail1">Bank</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="bank" value="<?php echo $res[0]['bank']; ?>">
                                </div>
                                <div class="col-md-4">
                                <label for="exampleInputEmail1">Branch</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="branch" value="<?php echo $res[0]['branch']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                            <div class="col-md-4">
                                    <label for="exampleInputEmail1">Earn</label> <i class="text-danger asterik">*</i><?php echo isset($error['earn']) ? $error['earn'] : ''; ?>
                                    <input type="text" class="form-control" name="earn" value="<?php echo $res[0]['earn']; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputEmail1"> Balance</label> <i class="text-danger asterik">*</i><?php echo isset($error['balance']) ? $error['balance'] : ''; ?>
                                    <input type="text" class="form-control" name="balance" value="<?php echo $res[0]['balance']; ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Plan Type</label> <i class="text-danger asterik">*</i>
                                    <select id='plan_type' name="plan_type" class='form-control'>
                                     <option value='' >None</option>
                                      <option value='shift' >Shift</option>
                                      <option value='new_plan' >New A1 Plan</option>
                                      <option value='free' >Free</option>
                                      
                                    </select>
                            </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                            <div class="col-md-3">
                                    <label for="exampleInputEmail1">Basic Wallet</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="basic_wallet" value="<?php echo $res[0]['basic_wallet']; ?>">
                                </div>
                                <div class="col-md-3">
                                <label for="exampleInputEmail1">Premium Wallet</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="premium_wallet" value="<?php echo $res[0]['premium_wallet']; ?>">
                                </div>
                                <div class="col-md-3">
                                <label for="exampleInputEmail1">Total Ads</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="total_ads" value="<?php echo $res[0]['total_ads']; ?>">
                                </div>
                                <div class="col-md-3">
                                <label for="exampleInputEmail1">Today Ads</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="today_ads" value="<?php echo $res[0]['today_ads']; ?>">
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
                                    <label for="exampleInputEmail1">Current Refers</label><i class="text-danger asterik">*</i>
                                       <input type="number" class="form-control" name="current_refers" value="<?php echo $res[0]['current_refers']; ?>">
                                    </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Target Refers</label><i class="text-danger asterik">*</i>
                                        <input type="number" class="form-control" name="target_refers" value="<?php echo $res[0]['target_refers']; ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                    <label for="exampleInputEmail1">Select Languages</label> <i class="text-danger asterik">*</i>
                                    <select id='support_lan' name="support_lan" class='form-control'>
                                    <option value=''>--Select--</option>
                                    <option value='tamil' <?php if ($res[0]['support_lan'] == 'tamil') echo 'selected'; ?>>Tamil</option>
                                      <option value='kannada' <?php if ($res[0]['support_lan'] == 'kannada') echo 'selected'; ?>>Kannada</option>
                                      <option value='telugu' <?php if ($res[0]['support_lan'] == 'telugu') echo 'selected'; ?>>Telugu</option>
                                      <option value='hindi' <?php if ($res[0]['support_lan'] == 'hindi') echo 'selected'; ?>>Hindi</option>
                                      <option value='english' <?php if ($res[0]['support_lan'] == 'english') echo 'selected'; ?>>English</option>
                                    </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                        <div class="col-md-3">
                                    <label for="exampleInputEmail1">Joined Date</label><i class="text-danger asterik">*</i>
                                    <input type="date" class="form-control" name="joined_date" value="<?php echo $res[0]['joined_date']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Min Withdrawal</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="min_withdrawal" value="<?php echo $res[0]['min_withdrawal']; ?>">
                                </div>
                                <div class="col-md-3">
                                   <label for="exampleInputEmail1">Gender</label> <i class="text-danger asterik">*</i>
                                    <select id='gender' name="gender" class='form-control'>
                                     <option value='male' <?php if ($res[0]['gender'] == 'male') echo 'selected'; ?>>Male</option>
                                      <option value='female' <?php if ($res[0]['gender'] == 'female') echo 'selected'; ?>>Female</option>
                                      <option value='others' <?php if ($res[0]['gender'] == 'others') echo 'selected'; ?>>Others</option>
                                    </select>
                                    </div>
                                    <div class="col-md-3">
                                    <label for="exampleInputEmail1">Device Id</label> <i class="text-danger asterik">*</i><?php echo isset($error['device_id']) ? $error['device_id'] : ''; ?>
                                    <input type="text" class="form-control" name="device_id" value="<?php echo $res[0]['device_id']; ?>">
                                </div>
						</div>
                                 
                                 <br>
                        <div class="row">
                        <div class="col-md-3">
                                    <label for="exampleInputEmail1">Old Plan</label> <i class="text-danger asterik">*</i><?php echo isset($error['old_plan']) ? $error['old_plan'] : ''; ?>
                                    <input type="text" class="form-control" name="old_plan" value="<?php echo $res[0]['old_plan']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Ads Time</label> <i class="text-danger asterik">*</i><?php echo isset($error['ads_time']) ? $error['ads_time'] : ''; ?>
                                    <input type="number" class="form-control" name="ads_time" value="<?php echo $res[0]['ads_time']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Ads Cost</label> <i class="text-danger asterik">*</i><?php echo isset($error['ads_cost']) ? $error['ads_cost'] : ''; ?>
                                    <input type="text" class="form-control" name="ads_cost" value="<?php echo $res[0]['ads_cost']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Worked Days</label> <i class="text-danger asterik">*</i><?php echo isset($error['worked_days']) ? $error['worked_days'] : ''; ?>
                                    <input type="number" class="form-control" name="worked_days" value="<?php echo $res[0]['worked_days']; ?>">
                                </div>
                            </div>
                             <br>
                            <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Blocked</label><br>
                                    <input type="checkbox" id="blocked_button" class="js-switch" <?= isset($res[0]['blocked']) && $res[0]['blocked'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="blocked" name="blocked" value="<?= isset($res[0]['blocked']) && $res[0]['blocked'] == 1 ? 1 : 0 ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                    <label for="exampleInputEmail1">Total Referrals</label> <i class="text-danger asterik">*</i><?php echo isset($error['total_referrals']) ? $error['total_referrals'] : ''; ?>
                                    <input type="text" class="form-control" name="total_referrals" value="<?php echo $res[0]['total_referrals']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Age</label> <i class="text-danger asterik">*</i><?php echo isset($error['age']) ? $error['age'] : ''; ?>
                                    <input type="text" class="form-control" name="age" value="<?php echo $res[0]['age']; ?>">
                                </div>
                                <div class="col-md-3">
                                   <label for="exampleInputEmail1">Project Type</label> <i class="text-danger asterik">*</i>
                                    <select id='project_type' name="project_type" class='form-control'>
                                     <option value='regular' <?php if ($res[0]['project_type'] == 'regular') echo 'selected'; ?>>regular</option>
                                      <option value='rejoin' <?php if ($res[0]['project_type'] == 'rejoin') echo 'selected'; ?>>rejoin</option>
                                      <option value='free' <?php if ($res[0]['project_type'] == 'free') echo 'selected'; ?>>free</option>
                                    </select>
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-3">
                                    <label for="exampleInputEmail1">performance</label> <i class="text-danger asterik">*</i><?php echo isset($error['performance']) ? $error['performance'] : ''; ?>
                                    <input type="text" class="form-control" name="performance" value="<?php echo $res[0]['performance']; ?>">
                                </div>
                                <div class="col-md-3">
                                   <label for="exampleInputEmail1">Platform Type</label> <i class="text-danger asterik">*</i>
                                    <select id='platform_type' name="platform_type" class='form-control'>
                                     <option value='app' <?php if ($res[0]['platform_type'] == 'app') echo 'selected'; ?>>app</option>
                                      <option value='web' <?php if ($res[0]['platform_type'] == 'web') echo 'selected'; ?>>web</option>
                                    </select>
                                    </div>
                                    <div class="col-md-3">
                                    <label for="exampleInputEmail1">Missed Days</label> <i class="text-danger asterik">*</i><?php echo isset($error['missed_days']) ? $error['missed_days'] : ''; ?>
                                    <input type="text" class="form-control" name="missed_days" value="<?php echo $res[0]['missed_days']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Order Id</label> <i class="text-danger asterik">*</i><?php echo isset($error['order_id']) ? $error['order_id'] : ''; ?>
                                    <input type="text" class="form-control" name="order_id" value="<?php echo $res[0]['order_id']; ?>">
                                </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                    <label for="exampleInputEmail1">Payment Verified</label> <i class="text-danger asterik">*</i><?php echo isset($error['payment_verified']) ? $error['payment_verified'] : ''; ?>
                                    <input type="text" class="form-control" name="payment_verified" value="<?php echo $res[0]['payment_verified']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Store Balance</label> <i class="text-danger asterik">*</i><?php echo isset($error['store_balance']) ? $error['store_balance'] : ''; ?>
                                    <input type="text" class="form-control" name="store_balance" value="<?php echo $res[0]['store_balance']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">City</label> <i class="text-danger asterik">*</i><?php echo isset($error['city']) ? $error['city'] : ''; ?>
                                    <input type="text" class="form-control" name="city" value="<?php echo $res[0]['city']; ?>">
                                </div>
                                <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Without Work</label><br>
                                    <input type="checkbox" id="without_button" class="js-switch" <?= isset($res[0]['without_work']) && $res[0]['without_work'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="without_work" name="without_work" value="<?= isset($res[0]['without_work']) && $res[0]['without_work'] == 1 ? 1 : 0 ?>">
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                    <div class="col-md-3">
                                    <label for="exampleInputEmail1">Max Withdrawal</label> <i class="text-danger asterik">*</i><?php echo isset($error['max_withdrawal']) ? $error['max_withdrawal'] : ''; ?>
                                    <input type="text" class="form-control" name="max_withdrawal" value="<?php echo $res[0]['max_withdrawal']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Old Balance</label> <i class="text-danger asterik">*</i><?php echo isset($error['old_balance']) ? $error['old_balance'] : ''; ?>
                                    <input type="text" class="form-control" name="old_balance" value="<?php echo $res[0]['old_balance']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Pay Later</label> <i class="text-danger asterik">*</i><?php echo isset($error['pay_later']) ? $error['pay_later'] : ''; ?>
                                    <input type="text" class="form-control" name="pay_later" value="<?php echo $res[0]['pay_later']; ?>">
                                </div>
                                <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Whatsapp Status</label><br>
                                    <input type="checkbox" id="whatsapp_button" class="js-switch" <?= isset($res[0]['whatsapp_status']) && $res[0]['whatsapp_status'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="whatsapp_status" name="whatsapp_status" value="<?= isset($res[0]['whatsapp_status']) && $res[0]['whatsapp_status'] == 1 ? 1 : 0 ?>">
                                </div>
                           </div>
                      </div>
                           <div class="row">
                           <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="">Free Income</label><br>
                                    <input type="checkbox" id="free_income_button" class="js-switch" <?= isset($res[0]['free_income']) && $res[0]['free_income'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="free_income" name="free_income" value="<?= isset($res[0]['free_income']) && $res[0]['free_income'] == 1 ? 1 : 0 ?>">
                                 </div>
                             </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="">Basic</label><br>
                                    <input type="checkbox" id="basic_button" class="js-switch" <?= isset($res[0]['basic']) && $res[0]['basic'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="basic" name="basic" value="<?= isset($res[0]['basic']) && $res[0]['basic'] == 1 ? 1 : 0 ?>">
                                 </div>
                             </div>
                             <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="">LifeTime</label><br>
                                    <input type="checkbox" id="lifetime_button" class="js-switch" <?= isset($res[0]['lifetime']) && $res[0]['lifetime'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="lifetime" name="lifetime" value="<?= isset($res[0]['lifetime']) && $res[0]['lifetime'] == 1 ? 1 : 0 ?>">
                                 </div>
                             </div>
                             <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="">Premium</label><br>
                                    <input type="checkbox" id="premium_button" class="js-switch" <?= isset($res[0]['premium']) && $res[0]['premium'] == 1 ? 'checked' : '' ?>>
                                    <input type="hidden" id="premium" name="premium" value="<?= isset($res[0]['premium']) && $res[0]['premium'] == 1 ? 1 : 0 ?>">
                                 </div>
                             </div>
                      </div>
                      <div class="row">
                                    <div class="col-md-3">
                                    <label for="exampleInputEmail1">Basic Days</label> <i class="text-danger asterik">*</i><?php echo isset($error['basic_days']) ? $error['basic_days'] : ''; ?>
                                    <input type="number" class="form-control" name="basic_days" value="<?php echo $res[0]['basic_days']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">LifeTime Days</label> <i class="text-danger asterik">*</i><?php echo isset($error['lifetime_days']) ? $error['lifetime_days'] : ''; ?>
                                    <input type="number" class="form-control" name="lifetime_days" value="<?php echo $res[0]['lifetime_days']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Premium Days</label> <i class="text-danger asterik">*</i><?php echo isset($error['premium_days']) ? $error['premium_days'] : ''; ?>
                                    <input type="number" class="form-control" name="premium_days" value="<?php echo $res[0]['premium_days']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Aadhaar Number</label> <i class="text-danger asterik"></i><?php echo isset($error['aadhaar_num']) ? $error['aadhaar_num'] : ''; ?>
                                    <input type="text" class="form-control" name="aadhaar_num" value="<?php echo $res[0]['aadhaar_num']; ?>">
                                </div>
                      </div>
                      <br>
                      <div class="row">
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Basic Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['basic_income']) ? $error['basic_income'] : ''; ?>
                                    <input type="number" class="form-control" name="basic_income" value="<?php echo $res[0]['basic_income']; ?>">
                                </div>
                                    <div class="col-md-3">
                                    <label for="exampleInputEmail1">LifeTime Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['lifetime_income']) ? $error['lifetime_income'] : ''; ?>
                                    <input type="number" class="form-control" name="lifetime_income" value="<?php echo $res[0]['lifetime_income']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Premium Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['premium_income']) ? $error['premium_income'] : ''; ?>
                                    <input type="number" class="form-control" name="premium_income" value="<?php echo $res[0]['premium_income']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Today Earn</label> <i class="text-danger asterik">*</i><?php echo isset($error['today_earn']) ? $error['today_earn'] : ''; ?>
                                    <input type="number" class="form-control" name="today_earn" value="<?php echo $res[0]['today_earn']; ?>">
                                </div>
                        </div>
                        <br>
                      <div class="row">
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Basic Joined Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['basic_joined_date']) ? $error['basic_joined_date'] : ''; ?>
                                    <input type="date" class="form-control" name="basic_joined_date" value="<?php echo $res[0]['basic_joined_date']; ?>">
                                </div>
                                    <div class="col-md-3">
                                    <label for="exampleInputEmail1">LifeTime Joined Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['lifetime_joined_date']) ? $error['lifetime_joined_date'] : ''; ?>
                                    <input type="date" class="form-control" name="lifetime_joined_date" value="<?php echo $res[0]['lifetime_joined_date']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Premium Joined Date</label> <i class="text-danger asterik">*</i><?php echo isset($error['premium_joined_date']) ? $error['premium_joined_date'] : ''; ?>
                                    <input type="date" class="form-control" name="premium_joined_date" value="<?php echo $res[0]['premium_joined_date']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Team Size</label> <i class="text-danger asterik">*</i><?php echo isset($error['team_size']) ? $error['team_size'] : ''; ?>
                                    <input type="number" class="form-control" name="team_size" value="<?php echo $res[0]['team_size']; ?>">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Valid Team</label> <i class="text-danger asterik">*</i><?php echo isset($error['valid_team']) ? $error['valid_team'] : ''; ?>
                                    <input type="number" class="form-control" name="valid_team" value="<?php echo $res[0]['valid_team']; ?>">
                                </div>
                                    <div class="col-md-3">
                                    <label for="exampleInputEmail1">Total Assets</label> <i class="text-danger asterik">*</i><?php echo isset($error['total_assets']) ? $error['total_assets'] : ''; ?>
                                    <input type="number" class="form-control" name="total_assets" value="<?php echo $res[0]['total_assets']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Level Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['level_income']) ? $error['level_income'] : ''; ?>
                                    <input type="number" class="form-control" name="level_income" value="<?php echo $res[0]['level_income']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Refer Level Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['refer_level_income']) ? $error['refer_level_income'] : ''; ?>
                                    <input type="number" class="form-control" name="refer_level_income" value="<?php echo $res[0]['refer_level_income']; ?>">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Valid</label> <i class="text-danger asterik">*</i><?php echo isset($error['valid']) ? $error['valid'] : ''; ?>
                                    <input type="number" class="form-control" name="valid" value="<?php echo $res[0]['valid']; ?>">
                                </div>
                                    <div class="col-md-3">
                                    <label for="exampleInputEmail1">Total Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['total_income']) ? $error['total_income'] : ''; ?>
                                    <input type="number" class="form-control" name="total_income" value="<?php echo $res[0]['total_income']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Team Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['team_income']) ? $error['team_income'] : ''; ?>
                                    <input type="number" class="form-control" name="team_income" value="<?php echo $res[0]['team_income']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Today Income</label> <i class="text-danger asterik">*</i><?php echo isset($error['today_income']) ? $error['today_income'] : ''; ?>
                                    <input type="number" class="form-control" name="today_income" value="<?php echo $res[0]['today_income']; ?>">
                                </div>
                        </div>
                        <br>
                        <div class="row">
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">C Refered By</label> <i class="text-danger asterik">*</i><?php echo isset($error['c_referred_by']) ? $error['c_referred_by'] : ''; ?>
                                    <input type="text" class="form-control" name="c_referred_by" value="<?php echo $res[0]['c_referred_by']; ?>">
                                 </div> 
                                 <div class="col-md-3">
                                    <label for="exampleInputEmail1">D Refered By</label> <i class="text-danger asterik">*</i><?php echo isset($error['d_referred_by']) ? $error['d_referred_by'] : ''; ?>
                                    <input type="text" class="form-control" name="d_referred_by" value="<?php echo $res[0]['d_referred_by']; ?>">
                                 </div>   
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
    var changeCheckbox = document.querySelector('#without_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#without_work').val(1);

        } else {
            $('#without_work').val(0);
        }
    };
</script>
<script>
    var changeCheckbox = document.querySelector('#blocked_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#blocked').val(1);

        } else {
            $('#blocked').val(0);
        }
    };
</script>
<script>
    var changeCheckbox = document.querySelector('#refer_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#refer_bonus_sent').val(1);

        } else {
            $('#refer_bonus_sent').val(0);
        }
    };
</script>
<script>
    var changeCheckbox = document.querySelector('#whatsapp_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#whatsapp_status').val(1);

        } else {
            $('#whatsapp_status').val(0);
        }
    };
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
<script>
    var changeCheckbox = document.querySelector('#free_income_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#free_income').val(1);

        } else {
            $('#free_income').val(0);
        }
    };
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>


