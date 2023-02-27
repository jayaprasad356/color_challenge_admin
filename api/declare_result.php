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
$yesterday_date = date('Y-m-d', strtotime('-1 day'));
$sql = "SELECT color_id, SUM(coins) as total_coins
FROM challenges 
WHERE DATE(datetime) = '$yesterday_date' 
GROUP BY color_id
HAVING SUM(coins) = (
  SELECT MIN(total_coins)
  FROM (
    SELECT SUM(coins) as total_coins
    FROM challenges
    WHERE DATE(datetime) = '$yesterday_date'
    GROUP BY color_id
  ) as sums
)
";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
$color_id=$res[0]['color_id'];
if ($num >= 1){
    $sql="SELECT * FROM challenges WHERE DATE(datetime)='$yesterday_date' AND color_id='$color_id'";
    $db->sql($sql);
    $res = $db->getResult();
    foreach($res as $row){
        $user_id=$row['user_id'];
        $coins=$row['coins'];
        $winning_coins=$coins *2;
        $sql="UPDATE users SET balance=balance + '$winning_coins' WHERE id='$user_id'";
        $db->sql($sql);
    }
    $sql="INSERT INTO results (`color_id`,`date`) VALUES ('$color_id','$yesterday_date')";
    $db->sql($sql);
    $sql="UPDATE challenges SET status=1 WHERE color_id='$color_id' AND DATE(datetime)='$yesterday_date'";
    $db->sql($sql);
    $sql="UPDATE challenges SET status=2 WHERE color_id !='$color_id' AND DATE(datetime)='$yesterday_date'";
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

