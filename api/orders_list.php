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

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql = "SELECT * FROM orders WHERE user_id = '$user_id'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1) {
    $productData = array();

    foreach ($res as $row) {
        $product_id = $row['product_id'];
        $delivery_date = $row['delivery_date']; 

        $sqlProduct = "SELECT * FROM product WHERE id = '$product_id'";
        $db->sql($sqlProduct);
        $products = $db->getResult();

        foreach ($products as $productRow) {
            $productData[] = array(
                'id' => $productRow['id'],
                'name' => $productRow['name'],
                'image' => DOMAIN_URL . $productRow['image'],
                'description' => $productRow['description'],
                'category_id' => $productRow['category_id'],
                'ads' => $productRow['ads'],
                'original_price' => $productRow['original_price'],
                'status' => $productRow['status'],
                'delivery_date' => $delivery_date 
            );
        }
    }

    $response['success'] = true;
    $response['message'] = "Orders Listed Successfully";
    $response['data'] = $productData;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "No Orders Found for the User";
    print_r(json_encode($response));
}
?>
