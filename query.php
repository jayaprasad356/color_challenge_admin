<?php
include_once('includes/crud.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

include_once('includes/custom-functions.php');
include_once('includes/functions.php');
$res = [];

if (isset($_GET['mobile'])) {
    $mobile = $db->escapeString($_GET['mobile']);

    $sql_query = "SELECT query.*, users.name FROM query LEFT JOIN users ON query.user_id = users.id WHERE users.mobile = '$mobile'";
    $db->sql($sql_query);
    $res = $db->getResult();
}

if (isset($_POST['btnAdd'])) {
    $title = $db->escapeString($_POST['title']);
    $description = $db->escapeString($_POST['description']);

    $sql_query = "INSERT INTO query (title, description) VALUES ('$title', '$description')";
    $db->sql($sql_query);

 $sql_query = "SELECT * FROM query WHERE title = '$title' AND description = '$description'";
 $db->sql($sql_query);
 $insertedData = $db->getResult();
} else {
 $errorMessage = 'Query insertion failed.';

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
                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile number">
                    </div>
                    <button type="submit" class="btn btn-primary">View</button>
                    <button type="button" class="btn btn-success" id="addQueryButton">Add Query</button>
                </form>
                <div class="card mt-3">
                    <div class="card-body">
                        <?php
                        if ($res) {
                            foreach ($res as $row) {
                                echo '<h5 class="card-title">' . $row['title'] . '</h5>';
                                echo '<p class="card-text">Description: ' . $row['description'] . '</p>';
                                echo '<p class="card-text">Status: ' . getStatusLabel($row['status']) . '</p>';
                                echo '<p class="card-text">Date and Time: ' . $row['datetime'] . '</p>';
                                echo '<p class="card-text">User Name: ' . $row['name'] . '</p>';
                                echo '<hr>';
                            }
                        } else {
                            echo 'No records found.';
                        }
                        function getStatusLabel($status) {
                            // Define status labels based on the status values.
                            $statusLabels = array(
                                '0' => 'Pending',
                                '1' => 'Completed',
                                '2' => 'Canceled',
                            );
                        
                            // Check if the status exists in the array, and return the label.
                            if (isset($statusLabels[$status])) {
                                return $statusLabels[$status];
                            } else {
                                return 'Unknown Status';
                            }
                        }
                        
                        ?>
                    </div>
                </div>
                <?php if (!empty($insertedData)): ?>
        <div class="card mt-3">
            <div class="card-body">
            <h4>Inserted Query Details</h4>
                    <h5 class="card-title"><?php echo $insertedData[0]['title']; ?></h5>
                    <p class="card-text">Description: <?php echo $insertedData[0]['description']; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addQueryModal" tabindex="-1" role="dialog" aria-labelledby="addQueryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addQueryModalLabel">Add Query</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalButton">
    <span aria-hidden="true">&times;</span>
</button>

                </div>
                <div class="modal-body">
                <form name="add_ads_form" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="clearFormButton">Clear</button>
                    <button type="submit" class="btn btn-primary" name="btnAdd">Save Query</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.min.js"></script>
    <script>
   $(document).ready(function () {
    $('#addQueryButton').click(function () {
        $('#addQueryModal').modal('show');
    });

    $('#clearFormButton').click(function () {
        $('#title').val('');
        $('#description').val('');
    });

    $('#closeModalButton').click(function () {
        $('#addQueryModal').modal('hide');
    });
    $('#mobile').on('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
   });
});

</script>

</body>
</html>