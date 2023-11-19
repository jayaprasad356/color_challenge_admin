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


$sql = "SELECT id FROM `users` WHERE plan = 'A1' AND old_plan = 0 AND status = 1";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){
    
    foreach ($res as $row) {
        $ID = $row['id'];
        $sql = "SELECT SUM(ads) AS ads,DATE(datetime) AS date,user_id FROM `transactions` WHERE user_id = $ID AND type = 'watch_ads' GROUP BY DATE(datetime)";
        $db->sql($sql);
        $res= $db->getResult();
        $num = $db->numRows($res);
        if ($num >= 1){
            foreach ($res as $row) {
                $user_id = $row['user_id'];
                $ads = $row['ads'];
                $date = $row['date'];
                if($ads >= 1200){
                    $sql = "UPDATE users SET total_targets = total_targets + 1 WHERE id = $user_id";
                    $db->sql($sql);

                }

                // $sql_query = "INSERT INTO daily_ads (user_id,ads,date)VALUES($user_id,$ads,'$date')";
                // $db->sql($sql_query);

            }

        }


    

    }
    $response['success'] = true;
    $response['message'] = "updated Successfully";
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Users Not found";
    print_r(json_encode($response));

}
?>