<?php
date_default_timezone_set('Asia/Kolkata');
$currentDate = date('Y-m-d');

if (isset($_POST['btnPaid']) && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        
        $datetime = date('Y-m-d H:i:s');
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $sql = "UPDATE whatsapp SET status = 1 WHERE id = $enable";
        $db->sql($sql);
      
         $sql = "SELECT user_id FROM whatsapp WHERE id = $enable";
         $db->sql($sql);
         $res = $db->getResult();
         $num = $db->numRows($res);
   
        
        if ($num >= 1) {
            $ID = $res[0]['user_id'];
            
            $sql = "SELECT  free_income, status FROM users WHERE status = 0 AND free_income = 1 AND id = '$ID'";
            $db->sql($sql);
            $res = $db->getResult();
            $num = $db->numRows($res);

            if ($num >= 1) {
                foreach ($res as $row) {
                    $free_income = $res[0]['free_income'];
                    $status = $row['status'];

                    if ($free_income == 1) {
                        $type = 'free_income';
                        $amount = 2;
                        

                        $sql = "SELECT id FROM transactions WHERE user_id = $ID AND type = '$type' AND DATE(datetime) = '$currentDate'";
                        $db->sql($sql);
                        $res= $db->getResult();
                        $num = $db->numRows($res);
                        if ($num == 0){
                            $sql = "UPDATE `users` SET `earn` = `earn` + $amount, `balance` = `balance` + $amount  WHERE `id` = $ID";
                            $db->sql($sql);
                    
                            $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$amount', '$datetime', '$type')";
                            $db->sql($sql);

                

                        }
                    }
                }
            }
            

            $sql = "SELECT basic, lifetime, premium, total_referrals, total_ads,lifetime_joined_date,referred_by,basic_joined_date,premium_joined_date FROM users WHERE status = 1 AND (basic = 1 OR premium = 1 OR lifetime = 1) AND id = '$ID'";
            $db->sql($sql);
            $res = $db->getResult();
            $num = $db->numRows($res);
            
            if ($num >= 1) {
                foreach ($res as $row) {
                    $basic = $res[0]['basic'];
                    $lifetime = $res[0]['lifetime'];
                    $premium = $res[0]['premium'];
                    $basic_joined_date = $res[0]['basic_joined_date'];
                    $lifetime_joined_date = $res[0]['lifetime_joined_date'];
                    $premium_joined_date = $res[0]['premium_joined_date'];
                    $total_referrals = $res[0]['total_referrals'];
                    $total_ads = $res[0]['total_ads'];
                    $referred_by = $res[0]['referred_by'];
                    $bal_ads = 36000 - $total_ads;

            
                    if ($basic == 1) {

                        $type = 'basic';
                        $amount = 25;

                        $additional_amount = $amount * 0.1;

                        $sql = "SELECT id FROM transactions WHERE user_id = $ID AND type = '$type' AND DATE(datetime) = '$currentDate'";
                        $db->sql($sql);
                        $res= $db->getResult();
                        $num = $db->numRows($res);
                        if ($num == 0){
                            $sql = "UPDATE `users` SET `earn` = `earn` + $amount, `balance` = `balance` + $amount,`basic_days` = `basic_days` + 1,`basic_income` = `basic_income` + $amount, `refer_level_income` = `refer_level_income` + $additional_amount  WHERE `id` = $ID";
                            $db->sql($sql);
                
                            $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$amount', '$datetime', '$type')";
                            $db->sql($sql);

                            if($basic_joined_date >= '2024-02-19' && $referred_by != ''){
                                $sql = "SELECT id FROM users WHERE refer_code = '$referred_by' AND status = 1 AND plan = 'A1U'";
                                $db->sql($sql);
                                $res = $db->getResult();
                                $num = $db->numRows($res);
                        
                                if ($num == 1) {
                                    $sql = "UPDATE `users` SET `earn` = `earn` + $additional_amount, `balance` = `balance` + $additional_amount,`level_income` = `level_income` + $additional_amount WHERE `refer_code` = '$referred_by'";
                                    $db->sql($sql);

                                    $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$additional_amount', '$datetime', 'level_bonus')";
                                    $db->sql($sql);
                                }

                            }



                

                        }
            

                    }
            
                    if ($lifetime == 1) {
                        if($lifetime_joined_date >= '2024-02-01'){
                            $type = 'lifetime';
                            $amount = 60;

                            $additional_amount = $amount * 0.1;
    
                            $sql = "SELECT id FROM transactions WHERE user_id = $ID AND type = '$type' AND DATE(datetime) = '$currentDate'";
                            $db->sql($sql);
                            $res= $db->getResult();
                            $num = $db->numRows($res);
                            if ($num == 0){
                                $sql = "UPDATE `users` SET `earn` = `earn` + $amount, `balance` = `balance` + $amount, `lifetime_days` = `lifetime_days` + 1, `lifetime_income` = `lifetime_income` + $amount, `refer_level_income` = `refer_level_income` + $additional_amount  WHERE `id` = $ID";
                                $db->sql($sql);
                    
                                $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$amount', '$datetime', '$type')";
                                $db->sql($sql);

                                if($lifetime_joined_date >= '2024-02-19' && $referred_by != ''){
                                    $sql = "SELECT id FROM users WHERE refer_code = '$referred_by' AND status = 1 AND plan = 'A1U'";
                                    $db->sql($sql);
                                    $res = $db->getResult();
                                    $num = $db->numRows($res);
                            
                                    if ($num == 1) {
                                        $sql = "UPDATE `users` SET `earn` = `earn` + $additional_amount, `balance` = `balance` + $additional_amount,`level_income` = `level_income` + $additional_amount WHERE `refer_code` = '$referred_by'";
                                        $db->sql($sql);

                                        $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$additional_amount', '$datetime', 'level_bonus')";
                                        $db->sql($sql);
                                    }

                                }
                    

                            }

                        }else{
                            if($total_referrals >= 15){
                                $ads = 6000;
                                $amount = 500;
                    
                            }
                            else if($total_referrals >= 10){
                                $ads = 1800;
                                $amount = 150;
                    
                            }
                            else if($total_referrals >= 5){
                                $ads = 900; 
                                $amount = 75;
                    
                            }else{
                                $ads = 600;
                                $amount = 50;
                            }
                    
                            if($bal_ads < 6000){
                                $ads = $bal_ads;
                                $amount = $bal_ads * 0.085;
                                
                            }
                            $datetime = date('Y-m-d H:i:s');
                            $type = 'lifetime';
                            $amount = $amount + 10;
    
    
            
                            $sql = "SELECT id FROM transactions WHERE user_id = $ID AND type = '$type' AND DATE(datetime) = '$currentDate'";
                            $db->sql($sql);
                            $res= $db->getResult();
                            $num = $db->numRows($res);
                            if ($num == 0){
                                $sql = "INSERT INTO transactions (`user_id`,`ads`,`amount`,`datetime`,`type`)VALUES('$ID','$ads','$amount','$datetime','$type')";
                                $db->sql($sql);
                                $res = $db->getResult();
                        
                                $sql = "UPDATE `users` SET  `today_ads` = today_ads + $ads,`total_ads` = total_ads + $ads,`earn` = earn + $amount,`balance` = balance + $amount, `refer_level_income` = `refer_level_income` + $additional_amount WHERE `id` = $ID";
                                $db->sql($sql);
                                $result = $db->getResult();

                                if($lifetime_joined_date >= '2024-02-19' && $referred_by != ''){
                                    $sql = "SELECT id FROM users WHERE refer_code = '$referred_by' AND status = 1 AND plan = 'A1U'";
                                    $db->sql($sql);
                                    $res = $db->getResult();
                                    $num = $db->numRows($res);
                            
                                    if ($num == 1) {
                                        $sql = "UPDATE `users` SET `earn` = `earn` + $additional_amount, `balance` = `balance` + $additional_amount,`level_income` = `level_income` + $additional_amount WHERE `refer_code` = '$referred_by'";
                                        $db->sql($sql);

                                        $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$additional_amount', '$datetime', 'level_bonus')";
                                        $db->sql($sql);
                                    }

                                }

    
                            }

                        }

                    }
            
                    if ($premium == 1) {
                        $type = 'premium';
                        $amount = 140;

                        $additional_amount = $amount * 0.1;

                        $sql = "SELECT id FROM transactions WHERE user_id = $ID AND type = '$type' AND DATE(datetime) = '$currentDate'";
                        $db->sql($sql);
                        $res= $db->getResult();
                        $num = $db->numRows($res);
                        if ($num == 0){
                            $sql = "UPDATE `users` SET `earn` = `earn` + $amount, `balance` = `balance` + $amount, `premium_days` = `premium_days` + 1, `premium_income` = `premium_income` + $amount, `refer_level_income` = `refer_level_income` + $additional_amount  WHERE `id` = $ID";
                            $db->sql($sql);
                
                            $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$amount', '$datetime', '$type')";
                            $db->sql($sql);

                            if($premium_joined_date >= '2024-02-19'){
                                $sql = "SELECT * FROM users WHERE refer_code = '$referred_by'";
                                $db->sql($sql);
                                $res = $db->getResult();
                                $num = $db->numRows($res);
                        
                                if ($num == 1) {
                                    $sql = "UPDATE `users` SET `earn` = `earn` + $additional_amount, `balance` = `balance` + $additional_amount,`level_income` = `level_income` + $additional_amount WHERE `refer_code` = '$referred_by'";
                                    $db->sql($sql);

                                    $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$additional_amount', '$datetime', 'level_bonus')";
                                    $db->sql($sql);
                                }

                            }



                        }
            

                    }

                    $sql = "UPDATE whatsapp SET status = 1 WHERE id = $enable";
                    $db->sql($sql);
                    $result = $db->getResult();
                }
            }
        }
    }
}

if (isset($_POST['btnCancel']) && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $sql = "UPDATE whatsapp SET status=2 WHERE id = $enable";
        $db->sql($sql);
        $result = $db->getResult();
    }
}
?>
<section class="content-header">
    <h1>Whatsapp /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
</section>

<section class="content">
    <form name="whatsapp_form" method="post" enctype="multipart/form-data">
        <!-- Main row -->
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header">
                        <!-- Status Filter -->
                        <div class="col-md-3">
                            <h4 class="box-title">Filter By Status</h4>
                            <select id='status' name="status" class='form-control'>
                                <option value="0">Not-Verified</option>
                                <option value="1">Verified</option>
                                <option value="2">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <h4 class="box-title">Filter By User Status</h4>
                            <select id='user_status' name="user_status" class='form-control'>
                            <option value="">Select</option>
                                <option value="0">Not-Verified</option>
                                <option value="1">Verified</option>
                                <option value="2">Blocked</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="row">
                            <div class="form-group">
                                <div class="text-left col-md-2">
                                    <input type="checkbox" onchange="checkAll(this)" name="chk[]"> Select All</input>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-success" name="btnPaid">Verified</button>
                                    <button type="submit" class="btn btn-danger" name="btnCancel">Rejected</button>
                                </div>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=whatsapp" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{"fileName": "students-list-<?= date('d-m-Y') ?>","ignoreColumn": ["operate"] }'>
                                <thead>
                                    <tr>
                                        <th data-field="column"> All</th>
                                        <th data-field="id" data-sortable="true">ID</th>
                                        <th data-field="name" data-sortable="true">Name</th>
                                        <th data-field="mobile" data-sortable="true">Mobile</th>
                                        <th data-field="status" data-sortable="true">Status</th>
                                        <th data-field="user_status" data-sortable="true">User Status</th>
                                        <th data-field="no_of_views" data-sortable="true">Views</th>
                                        <th data-field="datetime" data-sortable="true">Date time</th>
                                        <th data-field="image">Image</th>
                                        <th data-field="operate" data-events="actionEvents">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="separator"> </div>
            </div>
        </div>
    </form>
</section>


<script>
    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#community').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#status').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#user_status').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    function queryParams(p) {
        return {
            "status": $('#status').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            "user_status": $('#user_status').val(),
            "limit": p.limit,
            "sort": p.sort,
            "order": p.order,
            "offset": p.offset,
            "search": p.search,
        };
    }
</script>
<script>
    function checkAll(ele) {
        var checkboxes = document.getElementsByTagName('input');
        if (ele.checked) {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = true;
                }
            }
        } else {
            for (var i = 0; i < checkboxes.length; i++) {
                console.log(i)
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = false;
                }
            }
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('#user_id').select2({
            width: 'element',
            placeholder: 'Type in name to search',

        });
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>