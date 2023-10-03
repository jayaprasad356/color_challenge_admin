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



$sql = "SELECT * FROM `posts`";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['caption'] = $row['caption'];
        $temp['name'] = 'John Cena';
        $temp['image'] = DOMAIN_URL.'upload/post/'.$row['image'];
        $temp['likes'] = 0;
        $temp['share_link'] = 'https://admin.colorjobs.site/mypost.php?id='.$row['id'];
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "posts Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Not found";
    print_r(json_encode($response));

}