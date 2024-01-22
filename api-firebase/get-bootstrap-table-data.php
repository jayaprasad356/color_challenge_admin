<?php
session_start();

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;

// if session not set go to login page
if (!isset($_SESSION['username'])) {
    header("location:index.php");
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');

include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');
include_once('../includes/variables.php');
$db = new Database();
$currentdate = date('Y-m-d');
$db->connect();

        // Get the current date and time
        $date = new DateTime('now');

        // Round off to the nearest hour
        $date->modify('+' . (60 - $date->format('i')) . ' minutes');
        $date->setTime($date->format('H'), 0, 0);
    
        // Format the date and time as a string
        $date_string = $date->format('Y-m-d H:i:s');
        $currentdate = date('Y-m-d');
if (isset($_GET['table']) && $_GET['table'] == 'users') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['status']) && $_GET['status'] != '') {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= "status = '$status' ";
    }    
    if (isset($_GET['date']) && $_GET['date'] != '') {
        $date = $db->escapeString($fn->xss_clean($_GET['date']));
        if (!empty($where)) {
            $where .= "AND ";
        }
        $where .= "joined_date = '$date' ";
    }
    if (isset($_GET['referred_by']) && $_GET['referred_by'] != '') {
        $referred_by = $db->escapeString($fn->xss_clean($_GET['referred_by']));
        if (!empty($where)) {
            $where .= "AND ";
        }
        $where .= "referred_by = '$referred_by' ";
    }
    if (isset($_GET['plan']) && $_GET['plan'] != '') {
        $plan = $db->escapeString($fn->xss_clean($_GET['plan']));
        if (!empty($where)) {
            $where .= "AND ";
        }
        $where .= "plan = '$plan' ";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

     if (isset($_GET['search']) && !empty($_GET['search'])) {
         $search = $db->escapeString($fn->xss_clean($_GET['search']));
         $searchCondition = "name LIKE '%$search%' OR mobile LIKE '%$search%' OR status LIKE '%$search%' OR refer_code LIKE '%$search%'";
         $where = $where ? "$where AND $searchCondition" : $searchCondition;
     }
    
     $sqlCount = "SELECT COUNT(id) as total FROM users " . ($where ? "WHERE $where" : "");
     $db->sql($sqlCount);
     $resCount = $db->getResult();
     $total = $resCount[0]['total'];
    
     $sql = "SELECT * FROM users " . ($where ? "WHERE $where" : "") . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $support_id = $row['support_id'];
        $lead_id = $row['lead_id'];

        $operate = ' <a href="edit-users.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-users.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['email'] = $row['email'];
        $tempRow['fcm_id'] = $row['fcm_id'];
        $tempRow['total_referrals'] = $row['total_referrals'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['total_ads_viewed'] = $row['total_ads_viewed'];
        $tempRow['refer_code'] = $row['refer_code'];
        $tempRow['referred_by'] = $row['referred_by'];
        $tempRow['earn'] = $row['earn'];
        $tempRow['today_ads'] = $row['today_ads'];
        $tempRow['total_ads'] = $row['total_ads'];
        $tempRow['balance'] = $row['balance'];
        $tempRow['plan'] = $row['plan'];
        $tempRow['store_balance'] = $row['store_balance'];
        $sql = "SELECT name FROM `staffs` WHERE id = $support_id";
        $db->sql($sql);
        $res = $db->getResult();
        $support_name = isset($res[0]['name']) ? $res[0]['name'] :"";
        $sql = "SELECT name FROM `staffs` WHERE id = $lead_id";
        $db->sql($sql);
        $res = $db->getResult();
        $lead_name = isset($res[0]['name']) ? $res[0]['name'] :"";
        $tempRow['support_name'] = $support_name;
        $tempRow['lead_name'] = $lead_name;
        $tempRow['account_num'] = $row['account_num'];
        $tempRow['holder_name'] = $row['holder_name'];
        $tempRow['bank'] = $row['bank'];
        $tempRow['branch'] = $row['branch'];
        $tempRow['ifsc'] = $row['ifsc'];
        $tempRow['device_id'] = $row['device_id'];
        $tempRow['current_refers'] = $row['current_refers'];
        $target_ads = $row['worked_days'] * 1200;
        $tempRow['target_ads'] = "$target_ads" ;
        if($row['status']==0)
            $tempRow['status'] ="<label class='label label-default'>Not Verify</label>";
        elseif($row['status']==1)
            $tempRow['status']="<label class='label label-success'>Verified</label>";        
        else
            $tempRow['status']="<label class='label label-danger'>Blocked</label>";

         $tempRow['joined_date'] = $row['joined_date'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//challenges table goes here
if (isset($_GET['table']) && $_GET['table'] == 'challenges') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['date']) && $_GET['date'] != '') {
        $date = $db->escapeString($fn->xss_clean($_GET['date']));
        $new_format = 'Y-m-d';
        $converted_date = date($new_format, strtotime($date));
        $where .= "AND ch.`datetime` LIKE '" . $converted_date . "%' ";  
      }
      if (isset($_GET['time']) && $_GET['time'] != '') {
        $time = $db->escapeString($fn->xss_clean($_GET['time']));
        $where .= "AND TIME(ch.`datetime`) = '" . $time . "' ";  
    }
    if (isset($_GET['color_id']) && $_GET['color_id'] != '') {
        $color_id = $db->escapeString($fn->xss_clean($_GET['color_id']));
        $where .= "AND c.`id` = '" . $color_id . "' ";  
    }
    
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND u.mobile like '%" . $search . "%' OR c.name like '%" . $search . "%' OR c.code like '%" . $search . "%' OR ch.datetime like '%" . $search . "%' ";
        }
        
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    $join = "LEFT JOIN `colors` c ON ch.color_id = c.id LEFT JOIN `users` u ON ch.user_id = u.id WHERE ch.id IS NOT NULL ";

    $sql = "SELECT COUNT(*) as `total` FROM `challenges` ch $join " . $where . "";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
    
    $sql = "SELECT ch.id AS id,ch.*,c.name AS name,c.code AS code,u.mobile AS mobile,u.earn AS earn,ch.coins AS coins,u.name AS user_name FROM `challenges` ch $join 
    $where ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();
    
    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
    
        // $operate = '<a href="edit-transaction.php?id=' . $row['id'] . '" class="text text-primary"><i class="fa fa-edit"></i>Edit</a>';
        // $operate .= ' <a class="text text-danger" href="delete-transaction.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['name'] = $row['name'];
        $tempRow['earn'] = $row['earn'];
        $tempRow['user_name'] = $row['user_name']; // Add user name to the tempRow
        $tempRow['code'] = $row['code'];
        $tempRow['coins'] = $row['coins'];
        $tempRow['datetime'] = $row['datetime'];
    
        $rows[] = $tempRow;
    }
    
    $bulkData['rows'] = $rows;
    
    print_r(json_encode($bulkData));
}

if (isset($_GET['table']) && $_GET['table'] == 'analysis') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['date']) && $_GET['date'] != '') {
        $date = $db->escapeString($fn->xss_clean($_GET['date']));
        $new_format = 'Y-m-d';
        $converted_date = date($new_format, strtotime($date));
        $where .= "AND ch.`datetime` LIKE '" . $converted_date . "%' ";  
      }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        //$where .= "AND u.mobile like '%" . $search . "%' OR c.name like '%" . $search . "%' OR c.code like '%" . $search . "%' OR ch.datetime like '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    //$join = "LEFT JOIN `colors` c ON ch.color_id = c.id LEFT JOIN `users` u ON ch.user_id = u.id WHERE ch.id IS NOT NULL ";

    $sql = "SELECT COUNT(*) as `total` FROM `colors` " . $where . "";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT * FROM `colors`
    $where ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {
        $id = $row['id'];

        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $sql = "SELECT c.id FROM `challenges` c,`users` u WHERE c.user_id = u.id AND u.earn = 0 AND c.color_id = $id AND c.datetime = '$date_string'";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);
        $tempRow['nuc'] = $num;

        $sql = "SELECT c.id FROM `challenges` c,`users` u WHERE c.user_id = u.id AND u.earn != 0 AND c.color_id = $id AND c.datetime = '$date_string'";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);
        $tempRow['tuc'] = $num;

        $sql = "SELECT SUM(c.coins) AS coins FROM `challenges` c,`users` u WHERE c.user_id = u.id AND c.color_id = $id AND c.datetime = '$date_string'";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);
        $total_coins = $res[0]['coins'] * 2;
        $tempRow['ew'] = $total_coins;

        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'results') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "WHERE c.name like '%" . $search . "%' OR r.date like %" . $search . "%'";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);

    }        
    $join = "LEFT JOIN `colors` c ON r.color_id = c.id";

    $sql = "SELECT COUNT(*) as `total` FROM `results` r $join " . $where . "";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT r.id AS id,r.*,c.name AS name FROM `results` r $join 
          $where ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['datetime'] = $row['datetime'];
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

if (isset($_GET['table']) && $_GET['table'] == 'winners') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND c.name like '%" . $search . "%' OR u.mobile like '%" . $search . "%' OR c.date like '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);

    }        
    $sql = "SELECT COUNT(*) as `total` FROM `results` r " . $where . "";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT c.id AS id,c.coins *2 AS coins,cl.name AS name,u.mobile AS mobile,c.datetime AS date
    FROM challenges c
    JOIN colors cl ON c.color_id = cl.id
    JOIN users u ON c.user_id = u.id
    WHERE c.color_id IN (
      SELECT color_id
      FROM results
      WHERE DATE_FORMAT(results.datetime, '%Y-%m-%d') = DATE_FORMAT(c.datetime, '%Y-%m-%d')
    ) $where ORDER BY $sort $order LIMIT $offset, $limit ";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
            $tempRow['id'] = $row['id'];
            $tempRow['mobile'] = $row['mobile'];
            $tempRow['name'] = $row['name'];
            $tempRow['date'] = $row['date'];
            $tempRow['coins'] = $row['coins'];
            $rows[] = $tempRow;

    }        
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//withdrawals table goes here
if (isset($_GET['table']) && $_GET['table'] == 'withdrawals') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    
    if ((isset($_GET['status'])  && $_GET['status'] != '')) {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= "AND w.status=$status ";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= " AND (w.datetime LIKE '%" . $search . "%' OR u.mobile LIKE '%" . $search . "%' OR u.upi LIKE '%" . $search . "%' OR w.amount LIKE '%" . $search . "%' OR refer_code LIKE '%" . $search . "%' OR registered_date LIKE '%" . $search . "%')";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);

    }        
    $join = "WHERE w.user_id = u.id ";

    $sql = "SELECT COUNT(u.id) as `total` FROM `withdrawals` w,`users` u $join " . $where . "";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT w.id AS id,w.*,u.mobile,u.upi,u.account_num,u.holder_name,u.bank,u.branch,u.ifsc,u.earn,u.plan,u.performance,w.status AS status FROM `withdrawals` w,`users` u $join 
          $where ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
        $amount = $row['amount'];
        $tempRow['column'] = $checkbox;
        $tempRow['id'] = $row['id'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['earn'] = $row['earn'];
        $tempRow['upi'] = $row['upi'];
        $tempRow['account_num'] = ','.$row['account_num'].',';
        $tempRow['holder_name'] = $row['holder_name'];
        $tempRow['bank'] = $row['bank'];
        $tempRow['branch'] = $row['branch'];
        $tempRow['ifsc'] = $row['ifsc'];
        $tempRow['plan'] = $row['plan'];
        $tempRow['performance'] = $row['performance'];
        $amount = $row['amount'];

        if ($amount < 250) {
            $taxRate = 0.05; // 5% tax rate
        } elseif ($amount <= 500) {
            $taxRate = 0.1; // 10% tax rate
        } elseif ($amount <= 1000) {
            $taxRate = 0.15; // 15% tax rate
        } else {
            $taxRate = 0.2; // 20% tax rate
        }
        
        $taxAmount = $amount * $taxRate;
        $pay_amount = $amount - $taxAmount;
        $tempRow['pay_amount'] = $pay_amount;
        $tempRow['amount'] = $row['amount'];
        $tempRow['datetime'] = $row['datetime'];
        if($row['status']==1)
                $tempRow['status']="<p class='text text-success'>Paid</p>";        
        elseif($row['status']==0)
                 $tempRow['status']="<p class='text text-primary'>Unpaid</p>"; 
        else
               $tempRow['status']="<p class='text text-danger'>Cancelled</p>";
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//notifications table goes here
if (isset($_GET['table']) && $_GET['table'] == 'notifications') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "WHERE id like '%" . $search . "%' OR title like '%" . $search . "%' OR description like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `notifications`" . $where;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT * FROM notifications " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . "," . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = ' <a class="text text-danger" href="delete-notification.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['title'] = $row['title'];
        $tempRow['description'] = $row['description'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

if (isset($_GET['table']) && $_GET['table'] == 'daily_bonus') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'date';
    $order = 'DESC';
    
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND u.name like '%" . $search . "%' OR l.reason like '%" . $search . "%' OR l.id like '%" . $search . "%'  OR l.date like '%" . $search . "%' OR u.mobile like '%" . $search . "%' OR l.type like '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
   
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `daily_bonus` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
     $sql = "SELECT l.id AS id,l.*,u.name,u.mobile  FROM `daily_bonus` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['coins'] = $row['coins'];
        $tempRow['datetime'] = $row['datetime'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}


if (isset($_GET['table']) && $_GET['table'] == 'users_task') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'date';
    $order = 'DESC';
    
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND u.name like '%" . $search . "%' OR l.reason like '%" . $search . "%' OR l.id like '%" . $search . "%'  OR l.date like '%" . $search . "%' OR u.mobile like '%" . $search . "%' OR l.type like '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
   
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `users_task` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
     $sql = "SELECT l.id AS id,l.*,u.name,u.mobile  FROM `users_task` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['task_id'] = $row['task_id'];
        $tempRow['result'] = $row['result'];
        $tempRow['time'] = $row['time'];
        $tempRow['datetime'] = $row['datetime'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'transactions') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'date';
    $order = 'DESC';
    
    if ((isset($_GET['type']) && $_GET['type'] != '')) {
        $type = $db->escapeString($fn->xss_clean($_GET['type']));
        $where .= " AND l.type = '$type'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

       

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%') ";
        }
        
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
   
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `transactions` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
     $sql = "SELECT l.id AS id,l.*,u.name,u.mobile  FROM `transactions` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['type'] = $row['type'];
        $tempRow['amount'] = $row['amount'];
        $tempRow['ads'] = $row['ads'];
        $tempRow['datetime'] = $row['datetime'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'generate_coins') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'date';
    $order = 'DESC';
    
    if ((isset($_GET['type']) && $_GET['type'] != '')) {
        $type = $db->escapeString($fn->xss_clean($_GET['type']));
        $where .= " AND l.is_scratched = '$type'";
    }
    
    if (isset($_GET['date']) && $_GET['date'] != '') {
        $date = $db->escapeString($fn->xss_clean($_GET['date']));
        $where .= " AND l.date = '$date'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%') ";
        }
        
        
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
   
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `generate_coins` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
     $sql = "SELECT l.id AS id,l.*,u.name,u.mobile  FROM `generate_coins` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

       // $operate = '<a href="edit-scratch_cards.php?id=' . $row['id'] . '" class="text text-primary"><i class="fa fa-edit"></i>Edit</a>';
       // $operate .= ' <a class="text text-danger" href="delete-scratch_cards.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['coin_count'] = $row['coin_count'];
        $tempRow['start_time'] = $row['start_time'];
        $tempRow['end_time'] = $row['end_time'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'ads') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= "WHERE id like '%" . $search . "%' OR title like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `ads` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM ads " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-ads.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-ads.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['link'] = $row['link'];
        if (!empty($row['image'])) {
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
        } else {
            $tempRow['image'] = 'No Image';
        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'ads_trans') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'date';
    $order = 'DESC';
    
    if ((isset($_GET['type']) && $_GET['type'] != '')) {
        $type = $db->escapeString($fn->xss_clean($_GET['type']));
        $where .= " AND l.is_scratched = '$type'";
    }
    
    if (isset($_GET['date']) && $_GET['date'] != '') {
        $date = $db->escapeString($fn->xss_clean($_GET['date']));
        $where .= " AND l.date = '$date'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%') ";
        }
        
        
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
   
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `ads_trans` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
     $sql = "SELECT l.id AS id,l.*,u.name,u.mobile  FROM `ads_trans` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

       // $operate = '<a href="edit-scratch_cards.php?id=' . $row['id'] . '" class="text text-primary"><i class="fa fa-edit"></i>Edit</a>';
       // $operate .= ' <a class="text text-danger" href="delete-scratch_cards.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['ad_count'] = $row['ad_count'];
        $tempRow['start_time'] = $row['start_time'];
        $tempRow['end_time'] = $row['end_time'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'languages') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= "WHERE id like '%" . $search . "%' OR title like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `languages` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM languages " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-languages.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-languages.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//branches table goes here
if (isset($_GET['table']) && $_GET['table'] == 'branches') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "WHERE name like '%" . $search . "%' OR short_code like '%" . $search . "%' OR min_withdrawal like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `branches`" . $where;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT * FROM branches " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . "," . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        
         $operate = '<a href="edit-branches.php?id=' . $row['id'] . '" class="text text-primary"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-branches.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['short_code'] = $row['short_code'];
        $tempRow['support_lan'] = $row['support_lan'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//staffs table goes here
if (isset($_GET['table']) && $_GET['table'] == 'staffs') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if ((isset($_GET['status'])  && $_GET['status'] != '')) {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= "AND s.status='$status' ";
    }

    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND (s.mobile LIKE '%" . $search . "%' OR s.name LIKE '%" . $search . "%') ";
        }
        
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
  
    $join = "LEFT JOIN `branches` b ON s.branch_id = b.id WHERE s.id IS NOT NULL ";

    $sql = "SELECT COUNT(s.id) as total FROM `staffs` s $join " . $where . "";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT s.id AS id,s.*,b.short_code AS branch FROM `staffs` s $join 
    $where ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $staff_id = $row['id'];
        $operate = '<a href="edit-staff.php?id=' . $row['id'] . '" class="text text-primary"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-staff.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
    
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['balance'] = $row['balance'];
        $tempRow['email'] = $row['email'];
        $tempRow['branch'] = $row['branch'];
        $sql = "SELECT id FROM `incentives` WHERE DATE(datetime) = '$currentdate' AND amount = 50 AND staff_id = $staff_id GROUP BY user_id";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);
        $direct_join = $num;
        $sql = "SELECT id FROM `incentives` WHERE DATE(datetime) = '$currentdate' AND amount = 7.50 AND staff_id = $staff_id GROUP BY user_id";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);
        $today_refer_joins = $num;

        $sql = "SELECT COUNT(id) AS total FROM `users` WHERE support_id = $staff_id AND status = 1 AND today_ads != 0";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $res[0]['total'];
        $today_active_users = $num;
        $tempRow['today_direct_joins'] = $direct_join;
        $tempRow['today_refer_joins'] = $today_refer_joins;
        $tempRow['today_active_users'] = $today_active_users;
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'staff_withdrawals') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'w.id';
    $order = 'DESC';
    if ((isset($_GET['user_id']) && $_GET['user_id'] != '')) {
        $user_id = $db->escapeString($fn->xss_clean($_GET['user_id']));
        $where .= "AND w.staff_id = '$user_id'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND s.mobile like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    $join = "WHERE w.staff_id = s.id ";

    $sql = "SELECT COUNT(w.id) as total FROM `staff_withdrawals` w,`staffs` s $join ". $where ."";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $sql = "SELECT w.id AS id,w.*,s.name,s.mobile,s.balance,u.mobile,s.branch,s.bank_name,s.bank_account_number,s.ifsc_code FROM `staff_withdrawals` w,`staffs` s $join
                        $where ORDER BY $sort $order LIMIT $offset, $limit";
             $db->sql($sql);
        }
        else{
            $sql = "SELECT w.id AS id,w.*,s.name,s.balance,s.mobile,s.branch,s.bank_name,s.bank_account_number,s.ifsc_code FROM `staff_withdrawals` w,`staffs` s $join
                    $where ORDER BY $sort $order LIMIT $offset, $limit";
             $db->sql($sql);
        }
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        // $operate = ' <a class="text text-danger" href="delete-withdrawal.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        // $operate .= ' <a href="edit-withdrawal.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['amount'] = $row['amount'];
        $tempRow['datetime'] = $row['datetime'];
        $tempRow['bank_account_number'] = ','.$row['bank_account_number'].',';
        $tempRow['bank_name'] = $row['bank_name'];
        $tempRow['branch'] = $row['branch'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['balance'] = $row['balance'];
        $tempRow['ifsc_code'] = $row['ifsc_code'];
        $tempRow['column'] = $checkbox;
        if($row['status']==1)
            $tempRow['status'] ="<p class='text text-success'>Paid</p>";
        elseif($row['status']==0)
            $tempRow['status']="<p class='text text-primary'>Unpaid</p>";
        else
            $tempRow['status']="<p class='text text-danger'>Cancelled</p>";
        // $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}


//staff transactions table goes here
if (isset($_GET['table']) && $_GET['table'] == 'staff_transactions') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));
    if (isset($_GET['type']) && !empty($_GET['type'])){
        $type = $db->escapeString($fn->xss_clean($_GET['type']));
        $where .= "AND t.type = '$type' ";
      
    }
    if (isset($_GET['staff']) && !empty($_GET['staff'])) {
        $staff = $db->escapeString($fn->xss_clean($_GET['staff']));
        $where .= "AND s.id = '$staff' ";
    }
    
    

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND s.name like '%" . $search . "%' OR t.amount like '%" . $search . "%' OR t.id like '%" . $search . "%'  OR t.type like '%" . $search . "%' OR s.mobile like '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    $join = "LEFT JOIN `staffs` s ON t.staff_id = s.id WHERE t.id IS NOT NULL ";

    
    $sql = "SELECT COUNT(t.id) as total FROM `staff_transactions` t $join " . $where . "";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT t.id AS id,t.*,s.name,s.mobile FROM `staff_transactions` t $join 
    $where ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['amount'] = $row['amount'];
        $tempRow['datetime'] = $row['datetime'];
        $tempRow['type'] = $row['type'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'slide') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `slides` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM slides " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-slide.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-slide.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['link'] = $row['link'];
        if(!empty($row['image'])){
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";

        }else{
            $tempRow['image'] = 'No Image';

        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
/*if (isset($_GET['table']) && $_GET['table'] == 'post') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));
    if (isset($_GET['type']) && !empty($_GET['type'])){
        $type = $db->escapeString($fn->xss_clean($_GET['type']));
        $where .= "AND t.type = '$type' ";
      
    }
    if (isset($_GET['staff']) && !empty($_GET['staff'])) {
        $staff = $db->escapeString($fn->xss_clean($_GET['staff']));
        $where .= "AND s.id = '$staff' ";
    }
    
    

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND s.name like '%" . $search . "%' OR t.amount like '%" . $search . "%' OR t.id like '%" . $search . "%'  OR t.type like '%" . $search . "%' OR s.mobile like '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    $join = "LEFT JOIN `users` u ON p.user_id = u.id WHERE p.id IS NOT NULL ";

    
    $sql = "SELECT COUNT(p.id) as total FROM `posts` p $join " . $where . "";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT p.id AS id,p.*,u.name,u.refer_code FROM `posts` p $join 
    $where ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
           
        $operate = ' <a href="edit-post.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-slide.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['refer_code'] = $row['refer_code'];
        if (!empty($row['image'])) {
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
        } else {
            $tempRow['image'] = 'No Image';
        }
        $tempRow['caption'] = $row['caption'];
        $tempRow['likes'] = $row['likes'];
        if($row['status']==1)
        $tempRow['status'] ="<p class='text text-success'>Approved</p>";
    elseif($row['status']==0)
        $tempRow['status']="<p class='text text-danger'>Not-Approved</p>";
    else
        $tempRow['status']="<p class='text text-danger'>Cancelled</p>";
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'duplicate_sync') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'date';
    $order = 'DESC';
    
    if ((isset($_GET['type']) && $_GET['type'] != '')) {
        $type = $db->escapeString($fn->xss_clean($_GET['type']));
        $where .= " AND l.type = '$type'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

       

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%') ";
        }
        
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
   
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `duplicate_sync` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
     $sql = "SELECT l.id AS id,l.*,u.name,u.mobile  FROM `duplicate_sync` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['type'] = $row['type'];
        $tempRow['amount'] = $row['amount'];
        $tempRow['ads'] = $row['ads'];
        $tempRow['datetime'] = $row['datetime'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}*/
if (isset($_GET['table']) && $_GET['table'] == 'a1') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= "WHERE id like '%" . $search . "%' OR title like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `a1` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM a1 " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
       // $operate = ' <a href="edit-new_page.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
       // $operate .= ' <a class="text text-danger" href="delete-new_page.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['today_ads'] = $row['today_ads'];
        $tempRow['total_ads'] = $row['total_ads'];  
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'query') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'date';
    $order = 'ASC';
    
    if (isset($_GET['status']) && $_GET['status'] != '') {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= " AND l.status='$status'"; 
    }
    if (isset($_GET['preferences']) && $_GET['preferences'] != '') {
        $preferences = $db->escapeString($fn->xss_clean($_GET['preferences']));
        $where .= " AND l.preferences='$preferences'"; 
    }
    if (isset($_GET['title']) && $_GET['title'] != '') {
        $title = $db->escapeString($fn->xss_clean($_GET['title']));
        $where .= " AND l.title='$title'"; 
    }
  
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

       

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%') ";
        }
        
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
   
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `query` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
     $sql = "SELECT l.id AS id,l.*,u.name,u.mobile  FROM `query` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
         $operate = ' <a href="edit-query.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
       $operate .= ' <a class="text text-danger" href="delete-query.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
       
       if($row['status']==0){
        $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
    }
    else{
        $checkbox = '';
    }
       $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['title'] = $row['title'];
        $tempRow['description'] = $row['description'];
        $tempRow['datetime'] = $row['datetime'];
        $tempRow['reply'] = $row['reply'];
        $tempRow['preferences'] = $row['preferences'];
        if($row['status']==1)
        $tempRow['status'] ="<p class='text text-success'>completed</p>";
    elseif($row['status']==0)
        $tempRow['status']="<p class='text text-primary'>pending</p>";
    else
        $tempRow['status']="<p class='text text-danger'>Cancelled</p>";
        $tempRow['column'] = $checkbox;
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//hour withdrawal report table goes here
if (isset($_GET['table']) && $_GET['table'] == 'hour_withdrawal') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['date']) && $_GET['date'] != '') {
        $date = $db->escapeString($fn->xss_clean($_GET['date']));
        $where = " WHERE DATE(datetime) = '$date'";
    } else {
        $where = " WHERE DATE(datetime) = '2023-11-07'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND u.mobile like '%" . $search . "%' OR w.datetime like '%" . $search . "%' OR u.upi like '%" . $search . "%' OR w.amount like  '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }  
    $sql = "SELECT COUNT(`id`) as total FROM `withdrawals` " . $where;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
    
     $sql = "SELECT DATE_FORMAT(datetime, '%Y-%m-%d %H:00:00') AS hour_group, SUM(amount) AS total_withdrawal
     FROM `withdrawals`" . $where . " GROUP BY hour_group";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();

    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['hour_group'] = $row['hour_group'];
        $tempRow['total_withdrawal'] = $row['total_withdrawal'];
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    echo json_encode($bulkData);
}

if (isset($_GET['table']) && $_GET['table'] == 'missed_ads') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['offset'])) {
        $offset = (int) $db->escapeString($fn->xss_clean($_GET['offset']));
    }

    if (isset($_GET['limit'])) {
        $limit = (int) $db->escapeString($fn->xss_clean($_GET['limit']));
    }

    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    }

    if (isset($_GET['order'])) {
        $order = $db->escapeString($fn->xss_clean($_GET['order']));
    }

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= " AND (u.mobile LIKE '%" . $search . "%' OR t.datetime LIKE '%" . $search . "%' OR u.upi LIKE '%" . $search . "%' OR t.amount LIKE '%" . $search . "%')";
    }

    $join = "LEFT JOIN `users` u ON t.user_id = u.id". $where;

    $Sql = "SELECT COUNT(t.id) AS total FROM `transactions` t " . $join . " WHERE t.type = 'watch_ads' " . $where;
    $db->sql($Sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT u.mobile,SUM(t.ads) AS ads, DATE(t.datetime) AS datetime FROM `transactions` t  $join  WHERE t.type = 'watch_ads' $where  GROUP BY DATE(t.datetime)
            ORDER BY t.datetime DESC
            LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();

    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['ads'] = $row['ads'];
        if ($row['ads'] < 1200) {
            $tempRow['status'] = "<label class='label label-danger'>Missed</label>";
        } else {
            $tempRow['status'] = "<label class='label label-success'>Achieved</label>";
        }
        $tempRow['datetime'] = $row['datetime'];
        $rows[] = $tempRow;
    }

    $bulkData['rows'] = $rows;
    echo json_encode($bulkData);
}
if (isset($_GET['table']) && $_GET['table'] == 'approval_request') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['offset'])) {
        $offset = $db->escapeString($_GET['offset']);
    }

    if (isset($_GET['limit'])) {
        $limit = $db->escapeString($_GET['limit']);
    }

    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }

    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= " WHERE id LIKE '%" . $search . "%' OR title LIKE '%" . $search . "%'";
    }

    // Use COUNT(*) instead of COUNT(`id`)
    $sql = "SELECT COUNT(*) as total FROM `users` WHERE `status` = 1 AND `payment_verified` = 'request' " . $where;
    $db->sql($sql);
    $res = $db->getResult();
    $total = $res[0]['total'];

    $sql = "SELECT * FROM users WHERE `status` = 1 AND `payment_verified` = 'request' " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $operate = ' <a href="edit-user.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['order_id'] = $row['order_id'];
        $tempRow['payment_verified'] = $row['payment_verified'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }

    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//product table
if (isset($_GET['table']) && $_GET['table'] == 'product') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['category_id']) && $_GET['category_id'] != '') {
        $category_id = $db->escapeString($fn->xss_clean($_GET['category_id']));
        $where .= " AND l.category_id='$category_id'";
    }

    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= " AND (l.id LIKE '%" . $search . "%' OR l.name LIKE '%" . $search . "%')";
    }

    $join = "LEFT JOIN `category` c ON l.category_id = c.id";
    $whereClause = " WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `product` l " . $join . $whereClause;
    $db->sql($sql);
    $res = $db->getResult();
    
    foreach ($res as $row) {
        $total = $row['total'];
    }

    $sql = "SELECT l.id AS id, l.*, c.name AS category_name FROM `product` l " . $join . $whereClause . " ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {
        $operate = ' <a href="edit-product.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-product.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        if (!empty($row['image'])) {
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
        } else {
            $tempRow['image'] = 'No Image';
        }
        $tempRow['description'] = $row['description'];
        if($row['status']==1)
        $tempRow['status'] ="<p class='text text-success'>completed</p>";
        else
        $tempRow['status']="<p class='text text-primary'>pending</p>";
        $tempRow['category_name'] = $row['category_name'];
        $tempRow['ads'] = $row['ads'];
        $tempRow['original_price'] = $row['original_price'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }

    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//category table
if (isset($_GET['table']) && $_GET['table'] == 'category') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `category` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM category " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-category.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-category.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        if (!empty($row['image'])) {
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
        } else {
            $tempRow['image'] = 'No Image';
        }
        if($row['status']==1)
        $tempRow['status'] ="<p class='text text-success'>completed</p>";
        else
        $tempRow['status']="<p class='text text-primary'>pending</p>";
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//orders table
if (isset($_GET['table']) && $_GET['table'] == 'orders') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['status']) && $_GET['status'] != '') {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= " AND l.status='$status'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%' OR p.name LIKE '%" . $search . "%') ";
    }

    $join = "LEFT JOIN `users` u ON l.user_id = u.id LEFT JOIN `product` p ON l.product_id = p.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `orders` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT l.id AS id, l.*, u.name, u.mobile, p.name AS product_name FROM `orders` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = ' <a href="edit-orders.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-orders.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['product_name'] = $row['product_name'];
        $tempRow['delivery_date'] = $row['delivery_date'];
        if ($row['status'] == 0)
    $tempRow['status'] = "<label class='label label-default'>pending</label>";
elseif ($row['status'] == 1)
    $tempRow['status'] = "<label class='label label-success'>confirmed</label>";
elseif ($row['status'] == 2)
    $tempRow['status'] = "<label class='label label-info'>delivered</label>";
elseif ($row['status'] == 3)
    $tempRow['status'] = "<label class='label label-danger'>cancelled</label>";
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

if (isset($_GET['table']) && $_GET['table'] == 'verified_refer_users') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['offset'])) {
        $offset = $db->escapeString($_GET['offset']);
    }
    if (isset($_GET['limit'])) {
        $limit = $db->escapeString($_GET['limit']);
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= " AND (name LIKE '%" . $search . "%' OR mobile LIKE '%" . $search . "%' OR city LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%' OR refer_code LIKE '%" . $search . "%' OR registered_date LIKE '%" . $search . "%')";
    }

    $sql = "SELECT COUNT(`id`) as total FROM `users` WHERE unknown = 0 AND status = 0 ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM users WHERE unknown = 0 AND status = 0 AND referred_by != ''" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['registered_date'] = $row['registered_date'];
        $referred_by = $row['referred_by'];;
        $tempRow['referred_by'] = $row['referred_by'];
        $sql = "SELECT name,mobile,plan,balance FROM `users` WHERE refer_code = '$referred_by'";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);
        $refer_name = '';
        $refer_mobile = '';
       
        if($num >= 1){
            $refer_name = $res[0]['name'];
            $refer_mobile = $res[0]['mobile'];
            $refer_plan = $res[0]['plan'];
            $refer_balance = $res[0]['balance'];
            
        }
        
        $tempRow['refer_name'] = $refer_name;
        $tempRow['refer_mobile'] = $refer_mobile;
        $tempRow['refer_plan'] = $refer_plan;
        $tempRow['refer_balance'] = $refer_balance;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    echo json_encode($bulkData);
}
if (isset($_GET['table']) && $_GET['table'] == 'whatsapp') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['status']) && $_GET['status'] != '') {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= " AND l.status='$status'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= " AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%' OR u.mobile LIKE '%" . $search . "%')";
        }
        $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

        $sql = "SELECT COUNT(l.id) AS total FROM `whatsapp` l " . $join;
        $db->sql($sql);
        $res = $db->getResult();
        foreach ($res as $row) {
            $total = $row['total'];
        }
    
        $sql = "SELECT l.id AS id, l.*, u.name, u.mobile FROM `whatsapp` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
        $db->sql($sql);
        $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

       // $operate = ' <a href="edit-orders.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
       $operate = ' <a class="text text-danger" href="delete-whatsapp.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
       $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
       $tempRow['id'] = $row['id'];
       $tempRow['name'] = $row['name'];
       $tempRow['mobile'] = $row['mobile'];
       $tempRow['no_of_views'] = $row['no_of_views'];
       $tempRow['datetime'] = $row['datetime'];
       if($row['status']==1)
       $tempRow['status'] ="<p class='text text-success'>Verified</p>";
   elseif($row['status']==0)
       $tempRow['status']="<p class='text text-primary'>Not-Verified</p>";
   else
       $tempRow['status']="<p class='text text-danger'>Rejected</p>";
       // Assuming $row['image'] contains the image filename or path
       $imagePath = 'https://a1ads.site/' . $row['image'];
       $tempRow['image'] = '<a href="' . $imagePath . '" data-lightbox="category"><img src="' . $imagePath . '" alt="Image" width="70" height="70"></a>';
       
    $tempRow['column'] = $checkbox;
    $tempRow['operate'] = $operate;
    $rows[] = $tempRow;
}
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'payments') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['status']) && $_GET['status'] != '') {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= " AND l.status='$status'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%' OR p.name LIKE '%" . $search . "%') ";
    }

    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `payments` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
     $sql = "SELECT l.id AS id,l.*,u.name,u.mobile  FROM `payments` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

       // $operate = ' <a href="edit-orders.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
       // $operate .= ' <a class="text text-danger" href="delete-orders.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
       if($row['status']==0){
        $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
    }
    else{
        $checkbox = '';
    }
       $tempRow['id'] = $row['id'];
       $tempRow['name'] = $row['name'];
       $tempRow['mobile'] = $row['mobile'];
       $tempRow['order_id'] = $row['order_id'];
       if($row['status']==1)
       $tempRow['status'] ="<p class='text text-success'>Verified</p>";
   elseif($row['status']==0)
       $tempRow['status']="<p class='text text-primary'>Not-Verified</p>";
   else
       $tempRow['status']="<p class='text text-danger'>Cancelled</p>";
       // Assuming $row['image'] contains the image filename or path
    $imagePath = DOMAIN_URL . $row['payment_screenshot'];
    $tempRow['payment_screenshot'] = '<img src="' . $imagePath . '" alt="Image" width="70" height="70">';
    $tempRow['column'] = $checkbox;
    $rows[] = $tempRow;
}
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//Upload Jobs table
if (isset($_GET['table']) && $_GET['table'] == 'upload_jobs') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= " WHERE id LIKE '%" . $search . "%' OR user_name LIKE '%" . $search . "%'";
    }

    $sql = "SELECT COUNT(`id`) as total FROM `upload_jobs`" . $where;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT * FROM upload_jobs" . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();

    foreach ($res as $row) {
        $operate = '<a href="edit-upload_jobs.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-upload_jobs.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';

        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['user_name'] = $row['user_name'];
        $tempRow['company_name'] = $row['company_name'];
        $tempRow['title'] = $row['title'];
        $tempRow['description'] = $row['description'];
        $tempRow['deadline'] = $row['deadline'];
        $tempRow['price'] = $row['price'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }

    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}


//applied Jobs table
if (isset($_GET['table']) && $_GET['table'] == 'jobs') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['client_id']) && $_GET['client_id'] != '') {
        $client_id = $db->escapeString($fn->xss_clean($_GET['client_id']));
        $where .= " AND l.client_id='$client_id'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= " AND (l.id LIKE '%" . $search . "%' OR u.user_name LIKE '%" . $search . "%')";
    }

    $join = "LEFT JOIN `clients` c ON l.client_id = c.id WHERE l.id IS NOT NULL " . $where;

    $sqlCount = "SELECT COUNT(l.id) AS total FROM `jobs` l " . $join;
    $db->sql($sqlCount);
    $resCount = $db->getResult();
    $total = $resCount[0]['total'];
    
    $sqlSelect = "SELECT l.id AS id, l.*, c.name   
                FROM `jobs` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sqlSelect);
    $resSelect = $db->getResult();
    
    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    
    foreach ($resSelect as $row) {
      
    
        $operate = '<a href="edit-jobs.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-jobs.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
    
        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['highest_income'] = $row['highest_income'];
        $tempRow['title'] = $row['title'];
        $tempRow['description'] = $row['description'];
        $tempRow['total_slots'] = $row['total_slots'];
        $tempRow['appli_fees'] = $row['appli_fees'];
        $tempRow['slots_left'] = $row['slots_left'];
        if (!empty($row['ref_image'])) {
            $tempRow['ref_image'] = "<a data-lightbox='category' href='" . $row['ref_image'] . "' data-caption='" . $row['ref_image'] . "'><img src='" . $row['ref_image'] . "' title='" . $row['ref_image'] . "' height='70' /></a>";
        } else {
            $tempRow['ref_image'] = 'No Image';
        }
        if($row['status']==1)
        $tempRow['status'] ="<p class='text text-success'>Activated</p>";
        else
        $tempRow['status']="<p class='text text-danger'>Deactivated</p>";
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }

    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//enrolled just copy paste from users table for enrolled switch on and off 
if (isset($_GET['table']) && $_GET['table'] == 'enrolled') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['status']) && $_GET['status'] != '') {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= "status = '$status' ";
    }   
    if (isset($_GET['date']) && $_GET['date'] != '') {
        $date = $db->escapeString($fn->xss_clean($_GET['date']));
        if (!empty($where)) {
            $where .= "AND ";
        }
        $where .= "joined_date = '$date' ";
    }
    if (isset($_GET['enrolled']) && $_GET['enrolled'] != '') {
        $enrolled = $db->escapeString($fn->xss_clean($_GET['enrolled']));
        if (!empty($where)) {
            $where .= "AND ";
        }
        $where .= "enrolled = '$enrolled' ";
    }
    if (isset($_GET['referred_by']) && $_GET['referred_by'] != '') {
        $referred_by = $db->escapeString($fn->xss_clean($_GET['referred_by']));
        if (!empty($where)) {
            $where .= "AND ";
        }
        $where .= "referred_by = '$referred_by' ";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

     if (isset($_GET['search']) && !empty($_GET['search'])) {
         $search = $db->escapeString($fn->xss_clean($_GET['search']));
         $searchCondition = "name LIKE '%$search%' OR mobile LIKE '%$search%' OR status LIKE '%$search%' OR refer_code LIKE '%$search%'";
         $where = $where ? "$where AND $searchCondition" : $searchCondition;
     }
    
     $sqlCount = "SELECT COUNT(id) as total FROM users " . ($where ? "WHERE $where" : "");
     $db->sql($sqlCount);
     $resCount = $db->getResult();
     $total = $resCount[0]['total'];
    
     $sql = "SELECT * FROM users " . ($where ? "WHERE $where" : "") . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $support_id = $row['support_id'];
        $lead_id = $row['lead_id'];

        $operate = ' <a href="edit-enrolled.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-users.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['fcm_id'] = $row['fcm_id'];
        $tempRow['total_referrals'] = $row['total_referrals'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['total_ads_viewed'] = $row['total_ads_viewed'];
        $tempRow['refer_code'] = $row['refer_code'];
        $tempRow['referred_by'] = $row['referred_by'];
        $tempRow['earn'] = $row['earn'];
        $tempRow['today_ads'] = $row['today_ads'];
        $tempRow['total_ads'] = $row['total_ads'];
        $tempRow['balance'] = $row['balance'];
        $tempRow['store_balance'] = $row['store_balance'];
        $sql = "SELECT name FROM `staffs` WHERE id = $support_id";
        $db->sql($sql);
        $res = $db->getResult();
        $support_name = isset($res[0]['name']) ? $res[0]['name'] :"";
        $sql = "SELECT name FROM `staffs` WHERE id = $lead_id";
        $db->sql($sql);
        $res = $db->getResult();
        $lead_name = isset($res[0]['name']) ? $res[0]['name'] :"";
        $tempRow['support_name'] = $support_name;
        $tempRow['lead_name'] = $lead_name;
        $tempRow['account_num'] = $row['account_num'];
        $tempRow['holder_name'] = $row['holder_name'];
        $tempRow['bank'] = $row['bank'];
        $tempRow['branch'] = $row['branch'];
        $tempRow['ifsc'] = $row['ifsc'];
        $tempRow['device_id'] = $row['device_id'];
        $tempRow['current_refers'] = $row['current_refers'];
        $target_ads = $row['worked_days'] * 1200;
        $tempRow['target_ads'] = "$target_ads" ;
        if($row['status']==0)
            $tempRow['status'] ="<label class='label label-default'>Not Verify</label>";
        elseif($row['status']==1)
            $tempRow['status']="<label class='label label-success'>Verified</label>";        
        else
            $tempRow['status']="<label class='label label-danger'>Blocked</label>";

         $tempRow['joined_date'] = $row['joined_date'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}


//user jobs table
if (isset($_GET['table']) && $_GET['table'] == 'user_jobs') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= " AND (l.id LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%' OR u.mobile LIKE '%" . $search . "%')";
    }

    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `user_jobs` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row) {
        $total = $row['total'];
    }

    $sql = "SELECT l.id AS id, l.*, u.name, u.mobile FROM `user_jobs` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();

    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        if (!empty($row['image'])) {
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='70' /></a>";
        } else {
            $tempRow['image'] = 'No Image';
        }
        $rows[] = $tempRow;
    }

    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//Rewards table
if (isset($_GET['table']) && $_GET['table'] == 'result') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= " AND (l.id LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%' OR u.mobile LIKE '%" . $search . "%')";
    }

    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `result` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row) {
        $total = $row['total'];
    }

    $sql = "SELECT l.id AS id, l.*, u.name, u.mobile FROM `result` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();

    foreach ($res as $row) {

        $operate = '<a href="edit-result.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-result.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';

        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['user_jobs_id'] = $row['user_jobs_id'];
        $tempRow['position'] = $row['position'];
        $tempRow['income'] = $row['income'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }

    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
if (isset($_GET['table']) && $_GET['table'] == 'clients') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
    }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `clients` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM clients " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-clients.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-clients.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        if (!empty($row['profile'])) {
            $tempRow['profile'] = "<a data-lightbox='category' href='" . $row['profile'] . "' data-caption='" . $row['profile'] . "'><img src='" . $row['profile'] . "' title='" . $row['profile'] . "' height='50' /></a>";
        } else {
            $tempRow['profile'] = 'No Image';
        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//Rewards table
if (isset($_GET['table']) && $_GET['table'] == 'jobs_income') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['jobs_id']) && $_GET['jobs_id'] != '') {
        $jobs_id = $db->escapeString($fn->xss_clean($_GET['jobs_id']));
        $where .= " AND l.jobs_id = '$jobs_id'";
    }
  
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= " AND l.id LIKE '%" . $search . "%'";
        }  

    $join = "LEFT JOIN `jobs` j ON l.jobs_id = j.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `jobs_income` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row) {
        $total = $row['total'];
    }
    
    $sql = "SELECT l.id AS id, l.*, j.title FROM `jobs_income` l " . $join . $where . " ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();
    

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();

    foreach ($res as $row) {

        $operate = '<a href="edit-jobs_income.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-jobs_income.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';

        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['title'] = $row['title'];
        $tempRow['position'] = $row['position'];
        $tempRow['income'] = $row['income'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }

    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
$db->disconnect();

