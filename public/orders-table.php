
<section class="content-header">
    <h1>Orders /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
   
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
                        <h4 class="box-title">Filter by Status</h4>
                        <select id="status" name="status" class="form-control">
                            <option value="">All</option>
                            <option value="0">pending</option>
                            <option value="1">confirmed</option>
                            <option value="2">delivered</option>
                            <option value="3">cancelled</option>
                        </select>
                    </div>
                    </div>     
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=orders" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "product-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                    
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th data-field="name" data-sortable="true">Name</th>
                                    <th data-field="mobile" data-sortable="true">Mobile</th>
                                    <th data-field="product_name" data-sortable="true">Product Name</th>
                                    <th data-field="status" data-sortable="true">status</th>
                                    <th data-field="delivery_date" data-sortable="true">Delivery Date</th>
                                    <th  data-field="operate" data-events="actionEvents">Action</th>
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
    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#community').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#category_id').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#status').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
            "category_id": $('#category_id').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            "status": $('#status').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
</script>