<?php

if (isset($_POST['btnPaid'])  && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        
    
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $sql = "UPDATE approvals SET status=1 WHERE id = $enable";
        $db->sql($sql);
        $result = $db->getResult();
    }
}

?>
<section class="content-header">
    <h1>Approvals /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>

    <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-approvals.php"><i class="fa fa-plus-square"></i> Add New Approvals</a>
    </ol>

</section>
    <!-- Main content -->
    <section class="content">
    <form name="approval_form" method="post" enctype="multipart/form-data">
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <h4 class="box-title">Status</h4>
                            <select id='status' name="status" class='form-control'>
                            <option value="0">Not-Verfied</option>
                                <option value="1">Verified</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                                        <div class="form-group">
                                           <div class="text-left col-md-2">
                                                <input type="checkbox" onchange="checkAll(this)" name="chk[]" > Select All</input>
                                            </div> 
                                            <div class="col-md-3">
                                             <button type="submit" class="btn btn-success" name="btnPaid">Verfied</button>
                                          </div>

                                        </div>
                                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=approvals" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "yellow app-leaves-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                <th data-field="column"> All</th>
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th  data-field="mobile" data-sortable="true">Mobile</th>
                                    <th data-field="refer_bonus" data-sortable="true">Refer Bonus</th>
                                    <th data-field="referred_by" data-sortable="true">Refered By</th>
                                    <th  data-field="basic" data-sortable="true">Basic</th>
                                    <th  data-field="lifetime" data-sortable="true">LifeTime</th>
                                    <th  data-field="premium" data-sortable="true">Premium</th>
                                    <th  data-field="basic_joined_date" data-sortable="true">Basic Joined Date</th>
                                    <th  data-field="lifetime_joined_date" data-sortable="true">LifeTime Joined Date</th>
                                    <th  data-field="premium_joined_date" data-sortable="true">Premium Joined Date</th>
                                    <th  data-field="status" data-sortable="true">Status</th>
                                    <th  data-field="operate" data-events="actionEvents">Action</th>
                                </tr>
                            </thead>
                         </table>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="separator"> </div>
        </div>
        <!-- /.row (main row) -->
        </form>
</section>
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