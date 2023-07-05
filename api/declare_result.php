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
echo $date_string;

$yesterday_date = date('Y-m-d', strtotime('-1 day'));
$sql = "SELECT * FROM results WHERE datetime ='$date_string' ";
$db->sql($sql);
$res = $db->getResult();
$curr_min = date('i');
if (empty($res) && $curr_min == '00'){
  $sql = "SELECT color_id, SUM(coins) as total_coins
  FROM challenges 
  WHERE datetime = '$date_string' 
  GROUP BY color_id
  HAVING SUM(coins) = (
    SELECT MIN(total_coins)
    FROM (
      SELECT SUM(coins) as total_coins
      FROM challenges
      WHERE datetime = '$date_string'
      GROUP BY color_id
    ) as sums
  )
  ";
  $db->sql($sql);
  $res = $db->getResult();
  $num = $db->numRows($res);
  

  $sql = "SELECT id FROM challenges WHERE datetime = '$date_string' GROUP BY color_id ";
  $db->sql($sql);
  $gres = $db->getResult();
  $gnum = $db->numRows($gres);
  if ($num >= 1 && $gnum > 1){
      $color_id=$res[0]['color_id'];

  }
  else{
    $sql = "SELECT color_id FROM challenges ORDER BY RAND() LIMIT 1";
    $db->sql($sql);
    $res = $db->getResult();
    $color_id=$res[0]['color_id'];
  
  }
  $sql="SELECT * FROM challenges WHERE datetime ='$date_string' AND color_id='$color_id'";
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
  $sql="UPDATE challenges SET status=1 WHERE color_id='$color_id' AND datetime ='$date_string'";
  $db->sql($sql);
  $sql="UPDATE challenges SET status=2 WHERE color_id !='$color_id' AND datetime ='$date_string'";
  $db->sql($sql);
  $response['success'] = true;
  $response['message'] = "Result Announced Successfully";
  print_r(json_encode($response));
}


