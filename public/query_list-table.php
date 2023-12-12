<?php

$reply = '';

if (isset($_POST['enable']) && !empty($_POST['enable'])) {
    $_SESSION['query_id'] = $_POST['enable'][0];
}

$ID = $_SESSION['query_id'] ?? 0;

if (isset($_POST['btnEdit']) && isset($_POST['enable'])) {
    $reply = $db->escapeString($fn->xss_clean($_POST['reply']));

    if (!empty($ID)) {
        $sql_query = "UPDATE query SET reply = '$reply',status = 1 WHERE id = $ID";
        $db->sql($sql_query);
        $result = $db->getResult();
    }
}
?>

<section class="content-header">
    <h1>Query /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
</section>

<section class="content">
    <form name="withdrawal_form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <h4 class="box-title">Status</h4>
                            <select id='status' name="status" class='form-control'>
                                <option value="0">Pending</option>
                                <option value="1">Completed</option>
                                <option value="2">Rejected</option>
                            </select>
                        </div>

                        <!-- Preferences Filter -->
                        <div class="col-md-3">
                            <h4 class="box-title">Filter By Preferences</h4>
                            <select id='preferences' name="preferences" class='form-control'>
                                <option value="">--Select--</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>

                        <!-- Title Filter -->
                        <div class="col-md-3">
                            <h4 class="box-title">Filter By Title</h4>
                            <select id='title' name="title" class='form-control'>
                                <option value="">Select</option>
                                <option value="Register Issue">Register Issue</option>
                                <option value="Otp Issue">Otp Issue</option>
                                <option value="Ads Issue">Ads Issue</option>
                                <option value="Withdrawal Issue">Withdrawal Issue</option>
                                <option value="Refer Bonus Issue">Refer Bonus Issue</option>
                                <option value="Other Issue">Other Issue</option>
                            </select>
                        </div>

                        <!-- Reply Input -->
                        <div class='col-md-2'>
                            <label for="exampleInputEmail1">Reply</label> <i class="text-danger asterik">*</i>
                            <input type="text" class="form-control" name="reply" value="<?php echo $reply; ?>">
                        </div>

                        <!-- Update Button -->
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success" name="btnEdit">Update</button>
                        </div>
                    </div>
                

                <!-- Table Section -->
                <div class="box-body table-responsive">
                    <!-- Bootstrap Table -->
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=query" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                        "fileName": "Yellow app-notifications-list-<?= date('d-m-Y') ?>",
                        "ignoreColumn": ["operate"] 
                    }'>
                        <thead>
                                <tr>
                                <th data-field="column"> All</th>
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
        </form>
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
        "limit": p.limit,
        "sort": p.sort,
        "order": p.order,
        "offset": p.offset,
        "search": p.search,
        
    };
}


</script>
<script>
    $(document).ready(function () {
        $('#user_id').select2({
        width: 'element',
        placeholder: 'Type in name to search',

    });
    });

    if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

</script>
<script>
 function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
    
</script>


