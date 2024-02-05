<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');
include_once('../includes/crud.php');

$db = new Database();
$db->connect();

$datetime = date('Y-m-d H:i:s');
$currentdate = date('Y-m-d');

$response = array();

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    echo json_encode($response);
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    echo json_encode($response);
    return false;
}

$sql = "SELECT * FROM users WHERE status = 1 AND (basic = 1 OR premium = 1 OR lifetime = 1) AND ID = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = count($res);

if ($num >= 1) {
    foreach ($res as $row) {
        $ID = $row['id'];

        if ($row['basic'] == 1) {
            $type = 'basic';
            $amount = 25;

            $sql = "UPDATE `users` SET `earn` = `earn` + $amount, `balance` = `balance` + $amount, `basic_days` = `basic_days` + 1, `basic_income` = `basic_income` + $amount  WHERE `id` = $ID";
            $db->sql($sql);

            $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$amount', '$datetime', '$type')";
            $db->sql($sql);
        }

        if ($row['lifetime'] == 1) {
            $type = 'lifetime';
            $amount = 60;

            $sql = "UPDATE `users` SET `earn` = `earn` + $amount, `balance` = `balance` + $amount, `lifetime_days` = `lifetime_days` + 1, `lifetime_income` = `lifetime_income` + $amount  WHERE `id` = $ID";
            $db->sql($sql);

            $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$amount', '$datetime', '$type')";
            $db->sql($sql);
        }

        if ($row['premium'] == 1) {
            $type = 'premium';
            $amount = 140;

            $sql = "UPDATE `users` SET `earn` = `earn` + $amount, `balance` = `balance` + $amount, `premium_days` = `premium_days` + 1, `premium_income` = `premium_income` + $amount  WHERE `id` = $ID";
            $db->sql($sql);

            $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$ID', '$amount', '$datetime', '$type')";
            $db->sql($sql);
        }
    }
    $response['success'] = true;
    $response['message'] = "Transactions added successfully";
    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['message'] = "user not found";
    echo json_encode($response);
}
?>
