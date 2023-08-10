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


include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');
include_once('../includes/variables.php');
$db = new Database();
$db->connect();

if (isset($config['system_timezone']) && isset($config['system_timezone_gmt'])) {
    date_default_timezone_set($config['system_timezone']);
    $db->sql("SET `time_zone` = '" . $config['system_timezone_gmt'] . "'");
} else {
    date_default_timezone_set('Asia/Kolkata');
    $db->sql("SET `time_zone` = '+05:30'");
}
        // Get the current date and time
        $date = new DateTime('now');

        // Round off to the nearest hour
        $date->modify('+' . (60 - $date->format('i')) . ' minutes');
        $date->setTime($date->format('H'), 0, 0);
    
        // Format the date and time as a string
        $date_string = $date->format('Y-m-d H:i:s');
if (isset($_GET['table']) && $_GET['table'] == 'users') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';

    if (isset($_GET['status']) && $_GET['status'] != '') {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= "status = $status ";
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
        $where .= "WHERE name like '%" . $search . "%' OR mobile like '%'";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `users`" . $where;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT * FROM users " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . "," . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {

        $operate = ' <a href="edit-users.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['fcm_id'] = $row['fcm_id'];
        $tempRow['total_referrals'] = $row['total_referrals'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['total_ads_viewed'] = $row['total_ads_viewed'];

        $tempRow['earn'] = $row['earn'];
    
        $tempRow['balance'] = $row['balance'];
          $tempRow['account_num'] = $row['account_num'];
        $tempRow['holder_name'] = $row['holder_name'];
        $tempRow['bank'] = $row['bank'];
        $tempRow['branch'] = $row['branch'];
        $tempRow['ifsc'] = $row['ifsc'];
        $tempRow['device_id'] = $row['device_id'];
        $tempRow['generate_coin'] = $row['generate_coin'];
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
        $where .= "AND u.mobile like '%" . $search . "%' OR w.datetime like '%" . $search . "%' OR u.upi like '%" . $search . "%' OR w.amount like  '%" . $search . "%' ";
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

    $sql = "SELECT w.id AS id,w.*,u.mobile,u.upi,u.account_num,u.holder_name,u.bank,u.branch,u.ifsc,u.earn,w.status AS status FROM `withdrawals` w,`users` u $join 
          $where ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        if($row['status']==0){
            $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
        }
        else{
            $checkbox = '';
        }
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
        $tempRow['coins'] = $row['coins'];
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
$db->disconnect();
