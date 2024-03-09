
<section class="content-header">
    <h1>Users /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
            <ol class="breadcrumb">
                <a class="btn btn-block btn-default" href="add-userS.php"><i class="fa fa-plus-square"></i> Add New User</a>
</ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                <div class="col-md-2">
                <form action="export-users.php">
                            <button type='submit'  class="btn btn-primary"><i class="fa fa-download"></i> Export Users</button>
                        </form>
                        </div>
                        <div class="col-md-3">
                            <form action="export-verified-user.php">
                            <button type='submit'  class="btn btn-primary"><i class="fa fa-download"></i> Export verified users</button>
                        </form>
                        </div>
                        <form action="export-unverified-withdrawal-user.php">
                            <button type='submit'  class="btn btn-primary"><i class="fa fa-download"></i> Export Unverified Users Withdrawal Done</button>
                        </form>
                        <br>
                    <div class="col-md-2">
                        <h4 class="box-title">Filter by Status</h4>
                        <select id="status" name="status" class="form-control">
                            <option value="">All</option>
                            <option value="0">Non Verified</option>
                            <option value="1">Verified</option>
                            <option value="2">Blocked</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                                <h4 class="box-title">Basic Joined Date </h4>
                                <input type="date" class="form-control" id="basic_joined_date" name="basic_joined_date" value="<?php echo (isset($_GET['basic_joined_date'])) ? $_GET['basic_joined_date'] : "" ?>"></input>
                        </div>
                        <div class="col-md-3">
                                <h4 class="box-title">LifeTime Joined Date </h4>
                                <input type="date" class="form-control" id="lifetime_joined_date" name="lifetime_joined_date" value="<?php echo (isset($_GET['lifetime_joined_date'])) ? $_GET['lifetime_joined_date'] : "" ?>"></input>
                        </div>
                        <div class="col-md-3">
                                <h4 class="box-title">Premium Joined Date </h4>
                                <input type="date" class="form-control" id="premium_joined_date" name="premium_joined_date" value="<?php echo (isset($_GET['premium_joined_date'])) ? $_GET['premium_joined_date'] : "" ?>"></input>
                        </div>
                        <div class="col-md-2">
                        <h4 class="box-title">Referred By</h4>
                            <input type="text" class="form-control" name="referred_by" id="referred_by" >
                        </div>
                  


                </div>
                
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=users" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="true" data-export-types='["txt","csv"]' data-export-options='{
                            "fileName": "users-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                                <tr>
                                    <th  data-field="operate" data-events="actionEvents">Action</th>
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th  data-field="name" data-sortable="true">Name</th>
                                    <th  data-field="mobile" data-sortable="true">Mobile</th>
                                    <th data-field="refer_code" data-sortable="true">Refer Code</th>
                                    <th data-field="referred_by" data-sortable="true">Refered By</th>
                                    <th data-field="c_referred_by" data-sortable="true">C Referred By</th>
                                    <th data-field="d_referred_by" data-sortable="true">D Referred By</th>
                                    <th  data-field="total_referrals" data-sortable="true">Total Referals</th>
                                    <th  data-field="today_ads" data-sortable="true">Today Ads</th>
                                    <th  data-field="total_ads" data-sortable="true">Total Ads</th>
                                    <th  data-field="today_earn" data-sortable="true">Today Earn</th>
                                    <th  data-field="earn" data-sortable="true">Earn</th>
                                    <th  data-field="balance" data-sortable="true">Balance</th>
                                    <th  data-field="store_balance" data-sortable="true">Store Ads</th>
                                    <th  data-field="plan" data-sortable="true">Plan</th>
                                    <th  data-field="status" data-sortable="true">Status</th>
                                    <th  data-field="joined_date" data-sortable="true">Joined Date</th>
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
    $('#status').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#trail_completed').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#referred_by').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#plan').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#basic_joined_date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#lifetime_joined_date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#premium_joined_date').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
  /*  $('#free_income').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#basic').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#lifetime').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });
    $('#premium').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });*/
    function queryParams(p) {
        return {
            "basic_joined_date": $('#basic_joined_date').val(),
            "lifetime_joined_date": $('#lifetime_joined_date').val(),
            "premium_joined_date": $('#premium_joined_date').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            "status": $('#status').val(),
            "trail_completed": $('#trail_completed').val(),
            "referred_by": $('#referred_by').val(),
           // "free_income": $('#free_income').val(),
           // "basic": $('#basic').val(),
           // "lifetime": $('#lifetime').val(),
           // "premium": $('#premium').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
    
</script>


