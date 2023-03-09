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

$today_date=date('Y-m-d');

$date = new DateTime('now');

// Round down to the previous hour
$date->setTime($date->format('H'), 0, 0);

// Format the date and time as a string
$date_string = $date->format('Y-m-d H:i:s');

$yesterday_date = date('Y-m-d', strtotime('-1 day'));
$sql = "SELECT color_id, SUM(coins) as total_coins
FROM challenges 
WHERE c_date_time = '$date_string' 
GROUP BY color_id
HAVING SUM(coins) = (
  SELECT MAX(total_coins)
  FROM (
    SELECT SUM(coins) as total_coins
    FROM challenges
    WHERE c_date_time = '$date_string'
    GROUP BY color_id
  ) as sums
)
";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
$color_id=$res[0]['color_id'];
if ($num >= 1){
    $sql="SELECT * FROM challenges WHERE c_date_time ='$date_string' AND color_id='$color_id'";
    $db->sql($sql);
    $res = $db->getResult();
    foreach($res as $row){
        $user_id=$row['user_id'];
        $coins=$row['coins'];
        $winning_coins=$coins *2;
        $sql="UPDATE users SET balance=balance + '$winning_coins' WHERE id='$user_id'";
        $db->sql($sql);
    }
    $sql="INSERT INTO results (`color_id`,`datetime`) VALUES ('$color_id','$date_string')";
    $db->sql($sql);
    $sql="UPDATE challenges SET status=1 WHERE color_id='$color_id' AND c_date_time ='$date_string'";
    $db->sql($sql);
    $sql="UPDATE challenges SET status=2 WHERE color_id !='$color_id' AND c_date_time ='$date_string'";
    $db->sql($sql);
    $response['success'] = true;
    $response['message'] = "Result Announced Successfully";
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Results Not Found";
    print_r(json_encode($response));

}

