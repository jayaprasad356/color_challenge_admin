
<section class="content-header">
    <h1>Missed Ads /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
</section>

</section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-xs-12">
                <div class="box">
        
                    
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=missed_ads" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "challenges-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                <th data-field="mobile" data-sortable="true">Mobile</th>
                                <th data-field="ads" data-sortable="true">Ads</th>
                                    <th data-field="datetime" data-sortable="true">Date</th>
                                    <th data-field="status" data-sortable="true">Status</th>
        
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
  $('#date').on('change', function() {
    $('#users_table').bootstrapTable('refresh');
  });

function queryParams(p) {
    return {
        "date": $('#date').val(),
        "seller_id": $('#seller_id').val(),
        "community": $('#community').val(),
        "status": $('#status').val(),
        "trail_completed": $('#trail_completed').val(),
        "referred_by": $('#referred_by').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}


</script>
    
   
   