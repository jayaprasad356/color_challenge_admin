
<section class="content-header">
    <h1>Analysis /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <!-- <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-transaction.php"><i class="fa fa-plus-square"></i> Add New Transaction</a>
    </ol> -->
</section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-xs-12">
                <div class="box">
                    <!-- <div class="box-header">
                       <div class="form-group col-md-3">
                            <h4 class="box-title">Date </h4>
                            <input type="date" class="form-control" name="date" id="date" />
                        </div>
                    </div> -->
                    
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=analysis" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "challenges-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                    
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th  data-field="name" data-sortable="true">Color Name</th>
                                    <th  data-field="nuc" data-sortable="true">Non Earned Users Challenges</th>
                                    <th  data-field="tuc" data-sortable="true">Earned Users Challenges</th>
                                    <th  data-field="ew" data-sortable="true">Expect Withdrawls</th>
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

    $('#date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    // $('#manager_id').on('change', function() {
    //         id = $('#manager_id').val();
    //         $('#users_table').bootstrapTable('refresh');
    // });

    function queryParams(p) {
        return {
            "date": $('#date').val(),
            // "manager_id": $('#manager_id').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
    
</script>