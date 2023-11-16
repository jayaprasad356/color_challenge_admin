<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

include_once('includes/custom-functions.php');
include_once('includes/functions.php');
$res = [];
$transactionData = []; // New array to store transaction data

if (isset($_GET['mobile'])) {
    $mobile = $db->escapeString($_GET['mobile']);

    $sql_query = "SELECT id, total_ads, joined_date, worked_days, performance FROM users WHERE mobile = '$mobile'";
    $db->sql($sql_query);
    $userData = $db->getResult();

    if ($userData) {
        $res = $userData[0];
        $missed_ads = ($res['worked_days'] + 1) * 1200;
        $res['missed_ads'] = $missed_ads;

        $sql_query_transactions = "SELECT SUM(ads) AS ads, DATE(datetime) AS date FROM `transactions` WHERE user_id = " . $res['id'] . " AND type = 'watch_ads' GROUP BY DATE(datetime) ORDER BY datetime DESC";
        $db->sql($sql_query_transactions);
        $transactionData = $db->getResult();
    } else {
        echo 'User not found.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form class="form-inline" method="GET">
                    <div class="form-group mb-3">
                        <label for="mobileNumber" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile number" required>
                        <!-- Custom validation message -->
                        <div class="invalid-feedback" id="mobileValidationMessage">Mobile number is required.</div>
                    </div>
                    <button type="submit" class="btn btn-primary" id="viewButton">View</button>
                </form>
                <div class="card mt-3">
                    <div class="card-body">
                        <?php
                        if ($res) {
                            echo '<h5 class="card-text text-danger">Performance: ' . $res['performance'] . '</h5>';
                            echo '<p class="card-text">Total Ads: ' . $res['total_ads'] . '</p>';
                            echo '<p class="card-text">Joined Date: ' . $res['joined_date'] . '</p>';
                            echo '<p class="card-text">Worked Days: ' . $res['worked_days'] . '</p>';
                            echo '<p class="card-text">Missed Ads: ' . $res['missed_ads'] . '</p>';
                            
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-3">
                <div class="card-body">
                    <?php
                        if (!empty($transactionData)) {
                            echo '<h5 class="card-text">Transaction Details:</h5>';
                            echo '<table class="table table-bordered">';
                            echo '<thead><tr><th>Date</th><th>Ads</th></tr></thead>';
                            echo '<tbody>';
                            foreach ($transactionData as $transaction) {
                                echo '<tr>';
                                echo '<td>' . $transaction['date'] . '</td>';
                                echo '<td>' . $transaction['ads'] . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table>';
                        } else {
                            echo '<p class="card-text">No transaction data available.</p>';
                        }
                    ?>
            
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
