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

$currentdate = date('Y-m-d');
$sql = "SELECT ut.id,u.name,ut.time FROM `users_task` ut,users u WHERE u.id = ut.user_id AND DATE(ut.datetime) = '$currentdate' AND ut.time != 0 ORDER BY ut.time";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    $rank = 1;
$rows = array();
    foreach ($res as $row) {
        $temp['rank'] = (string)$rank;
        $temp['name'] = $row['name'];
        $temp['time'] = $row['time'];
        // Set 'prize' based on the rank
        switch ($rank) {
            case 1:
                $temp['prize'] = '5000';
                break;
            case 2:
                $temp['prize'] = '3000';
                break;
            case 3:
                $temp['prize'] = '1000';
                break;
            case 3:
                $temp['prize'] = '240';
                break;
            case 4:
                $temp['prize'] = '150';
                break;
            case 5:
                $temp['prize'] = '150';
                break;
            case 6:
                $temp['prize'] = '100';
                break;
            case 7:
                $temp['prize'] = '100';
                break;
            case 8:
                $temp['prize'] = '100';
                break;
            case 9:
                $temp['prize'] = '100';
                break;
            case 10:
                $temp['prize'] = '100';
                break;
            
            default:
                $temp['prize'] = '0'; // Or any other default value if needed
                break;
        }

        $rows[] = $temp;
        $rank++; 
    }
    $response['success'] = true;
    $response['message'] = "Contests Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Not found";
    print_r(json_encode($response));

}
