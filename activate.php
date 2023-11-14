<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

$refer_name = '';
$refer_mobile = '';

if (isset($_POST['btncheck'])) {
    $friend_refer_code = isset($_POST['friend_refer_code']) ? $db->escapeString($_POST['friend_refer_code']) : '';

    if (!empty($friend_refer_code)) {
        $sql_query = "SELECT name, mobile FROM users WHERE refer_code = '$friend_refer_code'";
        $db->sql($sql_query);

        $result = $db->getResult();

        if (!empty($result)) {
            $refer_name = isset($result[0]['name']) ? $result[0]['name'] : '';
            $refer_mobile = isset($result[0]['mobile']) ? $result[0]['mobile'] : '';
        }
    }
}

if (isset($_POST['btnAdd'])) {
    $mobile = isset($_POST['mobile']) ? $db->escapeString($_POST['mobile']) : '';
    $friend_refer_code = isset($_POST['friend_refer_code']) ? $db->escapeString($_POST['friend_refer_code']) : '';
    $order_id = isset($_POST['order_id']) ? $db->escapeString($_POST['order_id']) : '';

    if (!empty($mobile) && !empty($friend_refer_code) && !empty($order_id)) {
        $sql_query = "INSERT INTO activate (mobile, friend_refer_code, order_id) VALUES ('$mobile', '$friend_refer_code', '$order_id')";
        $db->sql($sql_query);
        header("Location: activate.php");
        exit();
    } else {
        echo "Please fill in all the required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Joiner</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
</head>
<body>

<div class="container mt-3" style="max-width: 500px;">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Add New Joiner</h2>
            <form action="#" method="post">
                <div class="form-group">
                    <label for="mobile">New Joiner Mobile number:</label>
                    <input type="tel" class="form-control" id="mobile" name="mobile" required>
                </div>

                <div class="form-group">
                    <label for="friend_refer_code">Friend Refer Code:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="friend_refer_code" name="friend_refer_code" required>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-danger" name="btncheck">Check</button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="order_id">Friend Mobile:</label>
                    <input type="text" class="form-control" name="refer_mobile" value="<?php echo $refer_mobile; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="order_id">Friend Name:</label>
                    <input type="text" class="form-control" name="refer_name" value="<?php echo $refer_name; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="order_id">Order ID:</label>
                    <input type="text" class="form-control" id="order_id" name="order_id" required>
                </div>
                <button type="submit" class="btn btn-primary" name="btnAdd">Request Activation</button>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

</body>
</html>
