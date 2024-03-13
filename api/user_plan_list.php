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
$currentdate = date('Y-m-d');
$db = new Database();
$db->connect();

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT user_plan.*, plan.image, plan.products, plan.invite_bonus, plan.price, plan.level_income, plan.total_income, plan.daily_income, plan.validity,DATEDIFF(NOW(), user_plan.joined_date) AS remaining_days
        FROM user_plan 
        LEFT JOIN plan ON user_plan.plan_id = plan.id
        WHERE user_plan.user_id = '$user_id'";

$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

function remaining_days($start_date, $end_date) {
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $remaining = $end->diff($start);
    return $remaining->format('%a');
}

if ($num >= 1) {
    foreach ($res as &$job) {
        $imagePath = $job['image'];
        $id = $job['id'];
        $imageURL = DOMAIN_URL . $imagePath;
        $job['image'] = $imageURL;
        $joined_date = $job['joined_date'];
        
        $sql = "SELECT COUNT(id) AS count FROM leaves WHERE date >= '$joined_date'  AND date <= '$currentdate'";
        $db->sql($sql);
        $res1 = $db->getResult();
        $num = $db->numRows($res1);
        $remaining = remaining_days($joined_date, $currentdate);

        if($id == 1){
            $total = 30;
        }else if($id == 2){
            $total = 60;
        }else{
            $total = 90;
        }

        $job['remaining_days'] = (string)($total - ($remaining - $num));
    }

    $response['success'] = true;
    $response['message'] = "User Plan Details Retrieved Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "Plan Not found";
    print_r(json_encode($response));
}
?>
