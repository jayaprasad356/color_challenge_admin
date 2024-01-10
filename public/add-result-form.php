<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
date_default_timezone_set('Asia/Kolkata');
?>
<?php
if (isset($_POST['btnAdd'])) {
    $user_id = $db->escapeString(($_POST['user_id']));
    $user_jobs_id = $db->escapeString(($_POST['user_jobs_id']));
    $position = $db->escapeString(($_POST['position']));
    $income = $db->escapeString(($_POST['income']));
    if (empty($user_id)) {
        $error['user_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($user_jobs_id)) {
        $error['user_jobs_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($position)) {
        $error['position'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($income)) {
        $error['income'] = " <span class='label label-danger'>Required!</span>";
    }
    if (!empty($user_id) && !empty($user_jobs_id) && !empty($position) && !empty($income)) {
        $sql_query = "INSERT INTO result (user_id,user_jobs_id,position,income)VALUES('$user_id','$user_jobs_id','$position','$income')";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }
        if ($result == 1) {
            $error['add_result'] = "<section class='content-header'>
                                    <span class='label label-success'>Result Added Successfully</span> </section>";
        } else {
            $error['add_result'] = " <span class='label label-danger'>Failed</span>";
        }
    }
}
?>
<section class="content-header">
    <h1>Add Result <small><a href='result.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Result</a></small></h1>
    <?php echo isset($error['add_result']) ? $error['add_result'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border"></div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="add_project_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                        <label for="">Users</label>
    <input type="text" id="details" name="user_id" class="form-control">
</div>
                        <div class="form-group">
                            <label for="">User Jobs ID</label>
                            <input type="number" class="form-control" name="user_jobs_id" id="user_jobs_id">
                        </div>
                        <div class="form-group">
                            <label for="">Position</label>
                            <input type="number" class="form-control" name="position" id="position">
                        </div>
                        <div class="form-group">
                            <label for="">Income</label>
                            <input type="number" class="form-control" name="income" id="income">
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="submit_btn" name="btnAdd">Submit</button>
                        <input type="reset" class="btn-warning btn" value="Clear" />
                    </div>
                    <div class="form-group">
                        <div id="result" style="display: none;"></div>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
        <!-- Left col -->
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Users</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-hover" data-toggle="table" id="users" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=users" data-click-to-select="true" data-side-pagination="server" data-pagination="true" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-trim-on-search="false" data-show-refresh="true" data-show-columns="true" data-sort-name="id" data-sort-order="asc" data-mobile-responsive="true" data-toolbar="#toolbar" data-show-export="true" data-maintain-selected="true" data-export-types='["txt","excel"]' data-export-options='{
                        "fileName": "users-list-<?= date('d-m-y') ?>",
                        "ignoreColumn": ["state"]
                    }'>
                        <thead>
                            <tr>
                                <th data-field="state" data-radio="true"></th>
                                <th data-field="id" data-sortable="true">ID</th>
                                <th data-field="name" data-sortable="true">Name</th>
                                <th data-field="balance" data-sortable="true">Balance</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="separator"> </div>
    </div>
</section>
<script>
  $('#users').on('check.bs.table', function(e, row) {
    $('#details').val(row.id + " | " + row.name + " | " + row.email);
    $('#user_id').val(row.id); // Update 'user_id' with the selected user's id
  });
</script>
