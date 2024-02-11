<section class="content-header">
    <h1>Approvals /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>

    <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-approvals.php"><i class="fa fa-plus-square"></i> Add New Approvals</a>
    </ol>

</section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=approvals" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "yellow app-leaves-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
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
</section>
<script>
    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#community').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
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
