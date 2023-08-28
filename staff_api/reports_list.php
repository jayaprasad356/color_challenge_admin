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
include_once('../includes/functions.php');
$fn = new functions;

$currentdate = date("Y-m-d");

if (empty($_POST['level'])) {
    $response['success'] = false;
    $response['message'] = "Level is empty";
    echo json_encode($response);
    return false;
}
if (empty($_POST['staff_id'])) {
    $response['success'] = false;
    $response['message'] = "staff id is empty";
    echo json_encode($response);
    return false;
}

$staff_id = $db->escapeString($_POST['staff_id']);
$level = $db->escapeString($_POST['level']);

if ($level == 1) {
    $sql = "SELECT * FROM users WHERE level = 1 AND support_id='$staff_id' AND status= 1";
    $db->sql($sql);
    $res = $db->getResult();
    $num = $db->numRows($res);
    if ($num >= 1) {
        $response['success'] = true;
        $response['message'] = "Users listed successfully";
        $response['data'] = $res;
        print_r(json_encode($response));
    } else {
        $response['success'] = false;
        $response['message'] = "No users found";
        print_r(json_encode($response));
    }
} elseif ($level == 2) {
    $sql = "SELECT * FROM users WHERE level = 2 AND support_id='$staff_id' AND status= 1";
    $db->sql($sql);
    $res = $db->getResult();
    $num = $db->numRows($res);
    if ($num >= 1) {
        $response['success'] = true;
        $response['message'] = "Users listed successfully";
        $response['data'] = $res;
        print_r(json_encode($response));
    } else {
        $response['success'] = false;
        $response['message'] = "No users found";
        print_r(json_encode($response));
    }
} elseif ($level == 3) {
    $sql = "SELECT * FROM users WHERE level = 3 AND support_id='$staff_id' AND status= 1";
    $db->sql($sql);
    $res = $db->getResult();
    $num = $db->numRows($res);
    if ($num >= 1) {
        $response['success'] = true;
        $response['message'] = "Users listed successfully";
        $response['data'] = $res;
        print_r(json_encode($response));
    } else {
        $response['success'] = false;
        $response['message'] = "No Users Found";
        print_r(json_encode($response));
    
    }
} elseif ($level == 4) {
        $sql = "SELECT * FROM users WHERE level = 4 AND  support_id='$staff_id' AND status= 1";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);
        if ($num >= 1) {
            $response['success'] = true;
            $response['message'] = "Users listed successfully";
            $response['data'] = $res;
            print_r(json_encode($response));
        } else {
            $response['success'] = false;
            $response['message'] = "No Users Found";
            print_r(json_encode($response));
        
        }
        
        }
        elseif ($level == 5) {
            $sql = "SELECT * FROM users WHERE level = 5 AND  support_id='$staff_id' AND status= 1";
            $db->sql($sql);
            $res = $db->getResult();
            $num = $db->numRows($res);
            if ($num >= 1) {
                $response['success'] = true;
                $response['message'] = "Users listed successfully";
                $response['data'] = $res;
                print_r(json_encode($response));
            } else {
                $response['success'] = false;
                $response['message'] = "No Users Found";
                print_r(json_encode($response));
            
            }
            
            }



?>