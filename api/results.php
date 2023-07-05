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


$sql = "SELECT * FROM results,colors WHERE results.color_id = colors.id ORDER BY results.id DESC LIMIT 20";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    foreach($res as $row){
        $temp['name']= $row['name'];
        $temp['code']= $row['code'];
        $date = date('j M, Y (g:i A)', strtotime($row['datetime']));
        $currentDate = date('j M, Y');
        $yesterdayDate = date('j M, Y', strtotime('yesterday'));
        
        if ($date == $currentDate) {
            $temp['datetime'] = 'Today';
        } elseif ($date == $yesterdayDate) {
            $temp['datetime'] = 'Yesterday';
        } else {
            $temp['datetime'] = $date;
        }
        
        $rows[]=$temp;
    }
    $response['success'] = true;
    $response['message'] = "Results Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Results Not Found";
    print_r(json_encode($response));

}
