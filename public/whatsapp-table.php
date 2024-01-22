<?php

if (isset($_POST['btnPaid'])  && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        
    
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));

        $sql = "SELECT user_id FROM whatsapp WHERE id = $enable";
        $db->sql($sql);
        $res= $db->getResult();
        $num = $db->numRows($res);
        if ($num >= 1){
            $ID = $res[0]['user_id'];
            $sql = "SELECT id,total_referrals,total_ads FROM users WHERE id = $ID AND status = 1 AND without_work = 1 AND plan = 'A1U' AND total_ads < 36000";
            $db->sql($sql);
            $res= $db->getResult();
            $num = $db->numRows($res);
            if ($num >= 1){
                $total_referrals = $res[0]['total_referrals'];
                $total_ads = $res[0]['total_ads'];
                $bal_ads = 36000 - $total_ads;
        
                if($total_referrals >= 15){
                    $ads = 6000;
                    $amount = 500;
        
                }
                else if($total_referrals >= 10){
                    $ads = 1800;
                    $amount = 150;
        
                }
                else if($total_referrals >= 5){
                    $ads = 900;
                    $amount = 75;
        
                }else{
                    $ads = 600;
                    $amount = 50;
                }
        
                if($bal_ads < 6000){
                    $ads = $bal_ads;
                    $amount = $bal_ads * 0.085;
                    
                }
                $datetime = date('Y-m-d H:i:s');
                $type = 'ad_bonus';
                $amount = $amount + 10;
        
        
                $sql = "INSERT INTO transactions (`user_id`,`ads`,`amount`,`datetime`,`type`)VALUES('$ID','$ads','$amount','$datetime','$type')";
                $db->sql($sql);
                $res = $db->getResult();
        
                $sql = "UPDATE `users` SET  `today_ads` = today_ads + $ads,`total_ads` = total_ads + $ads,`earn` = earn + $amount,`balance` = balance + $amount WHERE `id` = $ID";
                $db->sql($sql);
                $result = $db->getResult();
    
                $sql = "UPDATE whatsapp SET status = 1 WHERE id = $enable";
                $db->sql($sql);
                $result = $db->getResult();
            }


        }

    }
}

if (isset($_POST['btnCancel'])  && isset($_POST['enable'])) {
    for ($i = 0; $i < count($_POST['enable']); $i++) {
        
    
        $enable = $db->escapeString($fn->xss_clean($_POST['enable'][$i]));
        $sql = "UPDATE whatsapp SET status=2 WHERE id = $enable";
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
                                <option value="2">Rejected</option>
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
                                             <button type="submit" class="btn btn-danger" name="btnCancel">Rejected</button>
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

<script>
    $(document).ready(function () {
        $('#user_id').select2({
        width: 'element',
        placeholder: 'Type in name to search',

    });
    });

    if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

</script>