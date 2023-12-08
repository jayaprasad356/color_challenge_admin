<?php

$ID = isset($_SESSION['id']) ? intval($db->escapeString($_SESSION['id'])) : 0;

$data = array();
if (isset($_POST['btnUpdate'])  && isset($_POST['enable'])) {
    echo 'Hi';

 for ($i = 0; $i < count($_POST['enable']); $i++) {
    $reply = $db->escapeString($_POST['reply']);
     $enable = $db->escapeString($_POST['enable'][$i]);
     $sql_query = "UPDATE query SET reply='$reply',status = 1 WHERE id = $enable";
     $db->sql($sql_query);
 }
}
?>

<section class="content-header">
    <h1>Query /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>

</section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
                        <div class="col-md-2">
                                <h4 class="box-title">Status</h4>
                                <select id='status' name="status" class='form-control'>
                                        <option value="0">Pending</option>
                                        <option value="1">completed</option>
                                        <option value="2">Rejected</option>
                                </select>
                        </div>
                        <div class="col-md-3">
                                <h4 class="box-title">Filter By Preferences</h4>
                                <select id='preferences' name="preferences" class='form-control'>
                                       <option value="">--Select--</option>
                                        <option value="low">low</option>
                                        <option value="medium">medium</option>
                                        <option value="high">high</option>
                                </select>
                        </div>
                        <div class="col-md-3">
                                <h4 class="box-title">Filter By Title</h4>
                                <select id='title' name="title" class='form-control'>
                                <option value="">select</option>
                               <option value="Register Issue">Register Issue</option>
                               <option value="Otp Issue">Otp Issue</option>
                               <option value="Ads Issue">Ads Issue</option>
                               <option value="Withdrawal Issue">Withdrawal Issue</option>
                               <option value="Refer Bonus Issue">Refer Bonus Issue</option>
                               <option value="Other Issue">Other Issue</option>
                                </select>
                        </div>
                        <form method="post" method="post" enctype="multipart/form-data" >

                        <div class='col-md-2' id='replyField'>
                            <label for='exampleInputEmail1'>Reply</label> <i class='text-danger asterik'>*</i>
                            <input type='text' class='form-control' name='reply' value='<?php echo isset($res[0]['reply']) ? $res[0]['reply'] : ''; ?>'>
                        </div>
                        <div class='col-md-2'>
                            <button type='submit' class='btn btn-primary' name='btnUpdate'>Update</button>
                        </div>
                    </form>
                    </div>
                       <!-- /.box-header -->
                     
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=query" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "Yellow app-notifications-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                <th data-field="checkbox"> All</th>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="name" data-sortable="true">Name</th>
                                    <th data-field="mobile" data-sortable="true">Mobile</th>
                                    <th data-field="title" data-sortable="true">Title</th>
                                    <th data-field="description" data-sortable="true">Description</th>
                                    <th data-field="status" data-sortable="true">Status</th>
                                    <th data-field="datetime" data-sortable="true">Date Time</th>
                                    <th data-field="preferences" data-sortable="true">Preferences</th>
                                    <th data-field="reply" data-sortable="true">Reply</th>
                                    <th  data-field="operate" data-events="actionEvents">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="separator"> </div>
        </div>
        <!-- /.row (main row) -->
    </section>

  <!-- ... your existing HTML code ... -->

  <script>

$('#seller_id').on('change', function() {
    $('#products_table').bootstrapTable('refresh');
});
$('#community').on('change', function() {
    $('#users_table').bootstrapTable('refresh');
});
$('#status').on('change', function() {
    $('#users_table').bootstrapTable('refresh');
});
function queryParams(p) {
    return {
        "status": $('#status').val(),
        "seller_id": $('#seller_id').val(),
        "community": $('#community').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}

</script>
<script>
    function checkAll(source) {
        var checkboxes = document.getElementsByName('chk[]');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
        showHideReplyField(); // Call the function to show/hide the "Reply" field
    }

    // Function to show/hide the "Reply" field based on checkbox state
    function showHideReplyField() {
        var checkboxes = document.getElementsByName('chk[]');
        var replyField = document.getElementById('replyField');

        var atLeastOneChecked = false;

        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                atLeastOneChecked = true;
                break;
            }
        }

        // Show the reply field if at least one checkbox is checked
        if (atLeastOneChecked) {
            replyField.style.display = 'block';
        } else {
            // Hide the reply field if no checkbox is checked
            replyField.style.display = 'none';
        }
    }

    document.addEventListener('change', function (event) {
        if (event.target.name === 'chk[]') {
            showHideReplyField();
        }
    });

 
function submitForm(event) {
    // Add the following line to prevent the default form submission
    event.preventDefault();

    // Get the selected query IDs
    var selectedQueries = [];
    $("input[name='chk[]']:checked").each(function () {
        selectedQueries.push($(this).closest('tr').data('uniqueid'));
    });

 
    $('form').submit();
}

   
</script>







