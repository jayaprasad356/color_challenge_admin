
<section class="content-header">
    <h1>Jobs Income /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-jobs_income.php"><i class="fa fa-plus-square"></i> Add New Jobs Income</a>
    </ol> 
</section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                    <div class="row">
                                    <div class="form-group col-md-3">
                                            <h4 class="box-title">Filter by Jobs </h4>
                                            <select id='jobs_id' name="jobs_id" class='form-control'>
                                            <option value='' <?php echo isset($_GET['jobs_id']) && $_GET['jobs_id'] == 'all' ? 'selected="selected"' : ''; ?>>All Jobs</option>
                                                    <?php
                                                    $sql = "SELECT id FROM `jobs`";
                                                    $db->sql($sql);
                                                    $result = $db->getResult();
                                                    foreach ($result as $value) {
                                                    ?>
                                                        <option value='<?= $value['id'] ?>'><?= $value['id'] ?></option>
                                                <?php } ?>
                                            </select> 
                                    </div>
                                </div>
                    
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=jobs_income" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "challenges-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th  data-field="title" data-sortable="true">Title</th>
                                    <th  data-field="position" data-sortable="true">Position</th>
                                    <th  data-field="income" data-sortable="true">Income</th>
                                    <th data-field="operate">Action</th>
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
    $('#jobs_id').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
            "jobs_id": $('#jobs_id').val(),
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