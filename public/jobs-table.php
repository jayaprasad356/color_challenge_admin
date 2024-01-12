
<section class="content-header">
    <h1>Jobs /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-jobs.php"><i class="fa fa-plus-square"></i> Add New Jobs</a>
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
                                            <h4 class="box-title">Filter by Clients </h4>
                                                <select id='client_id' name="client_id" class='form-control'>
                                                <option value=''>All</option>
                                                
                                                        <?php
                                                        $sql = "SELECT id,name FROM `clients`";
                                                        $db->sql($sql);
                                                        $result = $db->getResult();
                                                        foreach ($result as $value) {
                                                        ?>
                                                            <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                        </div>
                                        </div>
                    
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=jobs" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "challenges-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th  data-field="title" data-sortable="true">Title</th>
                                    <th  data-field="description" data-sortable="true">Description</th>
                                    <th  data-field="name" data-sortable="true">Client Name</th>
                                    <th  data-field="total_slots" data-sortable="true">Total Slots</th>
                                    <th  data-field="appli_fees" data-sortable="true">Application Fees</th>
                                    <th  data-field="slots_left" data-sortable="true">Slots Left</th>
                                    <th  data-field="highest_income" data-sortable="true">Highest Income</th>
                                    <th  data-field="status" data-sortable="true">Status</th>
                                    <th  data-field="ref_image">Reference Image</th>
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

    $('#date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#client_id').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    // $('#manager_id').on('change', function() {
    //         id = $('#manager_id').val();
    //         $('#users_table').bootstrapTable('refresh');
    // });

    function queryParams(p) {
        return {
            "date": $('#date').val(),
            "client_id": $('#client_id').val(),
            // "manager_id": $('#manager_id').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
    
</script>