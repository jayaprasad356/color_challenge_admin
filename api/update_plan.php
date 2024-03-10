<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();


$sql = "SELECT * FROM  users WHERE  basic = 1 OR lifetime = 1 OR premium = 1";
$db->sql($sql);
$res = $db->getResult();

if (!empty($res)) {
    foreach ($res as $row) {
        $user_id = $row['id'];
        

        if ($row['basic'] == 1) {
            $plan_id = 1;
            $joined_date = $row['basic_joined_date'];
            $income = $row['basic_income'];
            $sql_insert_user_plan = "INSERT INTO user_plan (user_id, plan_id, income, joined_date)  VALUES ($user_id, $plan_id, $income, '$joined_date')";
            $db->sql($sql_insert_user_plan);
        }

        if ($row['lifetime'] == 1) {
            $plan_id = 2;
            $joined_date = $row['lifetime_joined_date'];
            $income = $row['lifetime_income'];
            $sql_insert_user_plan = "INSERT INTO user_plan (user_id, plan_id, income, joined_date)  VALUES ($user_id, $plan_id, $income, '$joined_date')";
            $db->sql($sql_insert_user_plan);
        }

        if ($row['premium'] == 1) {
            $plan_id = 3;
            $joined_date = $row['premium_joined_date'];
            $income = $row['premium_income'];
            $sql_insert_user_plan = "INSERT INTO user_plan (user_id, plan_id, income, joined_date)  VALUES ($user_id, $plan_id, $income, '$joined_date')";
            $db->sql($sql_insert_user_plan);
        }
    }
}

$response['success'] = true;
$response['message'] = "User plans inserted successfully";
print_r(json_encode($response));
?>
