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

                    </div>
                    
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=query" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "Yellow app-notifications-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    
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
    $('#preferences').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#title').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
 
    function queryParams(p) {
        return {
            "category_id": $('#category_id').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            "status": $('#status').val(),
            "preferences": $('#preferences').val(),
            "title": $('#title').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
</script>
