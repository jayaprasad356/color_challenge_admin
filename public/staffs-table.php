
<section class="content-header">
    <h1>Staffs /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>

</section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-12">
                <div class="box">
                    <div class="box-header">
                            <div class="row">
                                    <div class="form-group col-md-3">
                                            <h4 class="box-title">Filter by Status </h4>
                                            <select id='status' name="status" class='form-control'>
                                                <option value="">All</option>
                                                <option value="0">Not-verified</option>
                                                <option value="1">Verified</option>
                                                <option value="2">Cancelled</option>
                                            </select> 
                                    </div>
                            </div>
                        </div>

                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=staffs" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "Yellow app-staffs-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    <th data-field="operate">Action</th>
                                    <th data-field="id" data-sortable="true">ID</th>
                                    <th data-field="name" data-sortable="true">Name</th>
                                    <th data-field="mobile" data-sortable="true">Mobile</th>
                                    <th data-field="email" data-sortable="true">Email</th>
                                    <th data-field="status" data-sortable="true">Status</th>
                                    <th data-field="balance" data-sortable="true">Balance</th>
                                    <th data-field="branch" data-sortable="true">Branch</th>
                                    <th data-field="join_date" data-sortable="true">Join Date</th>
                                    <th data-field="salary_date" data-sortable="true">Salary Date</th>
                                    <th data-field="weekly_target" data-sortable="true">Weekly Target</th>
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
    // $('#seller_id').on('change', function() {
    //     $('#products_table').bootstrapTable('refresh');
    // });
    $('#status').on('change', function() {
            idf = $('#status').val();
            $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
            "status": $('#status').val(),
            // "seller_id": $('#seller_id').val(),
            // "community": $('#community').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
</script>
