<?php

if (isset($_POST['btnPaid'])  && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        
    
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $sql = "UPDATE whatsapp SET status=1 WHERE id = $enable";
        $db->sql($sql);
        $result = $db->getResult();
    }
}


?>
<section class="content-header">
    <h1>Whatsapp /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>

</section>

<section class="content">
    <!-- Main row -->
    <div class="row">
        <div class="col-12">
            <form method="post" action="">
                <div class="box">
                    <div class="box-header">
                        <!-- Status Filter -->
                        <div class="col-md-2">
                            <h4 class="box-title">Status</h4>
                            <select id='status' name="status" class='form-control'>
                                <option value="0">Not-Verified</option>
                                <option value="1">Verified</option>
                                <option value="2">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div  class="box-body table-responsive">
                                    <div class="row">
                                        <div class="form-group">
                                           <div class="text-left col-md-2">
                                                <input type="checkbox" onchange="checkAll(this)" name="chk[]" > Select All</input>
                                            </div> 
                                            <div class="col-md-3">
                                             <button type="submit" class="btn btn-success" name="btnPaid">Verified</button>
                                          </div>

                                        </div>
                                    </div>
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=whatsapp" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "students-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                <th data-field="column"> All</th>
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th data-field="name" data-sortable="true">Name</th>
                                    <th data-field="mobile" data-sortable="true">Mobile</th>
                                    <th data-field="status" data-sortable="true">Status</th>
                                    <th  data-field="image">Image</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator"> </div>
        </div>
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

    function checkAll(ele) {
        var checkboxes = document.querySelectorAll('input[name="enable"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = ele.checked;
        });
    }
</script>