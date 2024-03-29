<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

$sql = "SELECT id,name FROM branches ORDER BY id ASC";
$db->sql($sql);
$res = $db->getResult();

?>
<?php
if (isset($_POST['btnAdd'])) {


    $user_id = $db->escapeString(($_POST['user_id']));
    $amount = $db->escapeString(($_POST['amount']));
    $error = array();
   
    if (empty($user_id)) {
        $error['user_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($amount)) {
        $error['amount'] = " <span class='label label-danger'>Required!</span>";
    }
  
   
   
    if (!empty($user_id) && !empty($amount)) 
    {
           
            $sql_query = "INSERT INTO scratch_cards (user_id,amount)VALUES('$user_id','$amount')";
            $db->sql($sql_query);
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }
            if ($result == 1) {
                
                $error['add_branches'] = "<section class='content-header'>
                                                <span class='label label-success'>Scratch Cards Added Successfully</span> </section>";
            } else {
                $error['add_branches'] = " <span class='label label-danger'>Failed</span>";
        }
        }
}
?>
    
<section class="content-header">
    <h1>Add New Scartch Cards<small><a href='scratch_cards.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Scartch Cards</a></small></h1>

    <?php echo isset($error['add_branches']) ? $error['add_branches'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form url="add_branches_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                       <div class="row">
                            <div class="form-group">
                                <div class='col-md-10'>
                                    <label for="exampleInputEmail1">User ID</label> <i class="text-danger asterik">*</i><?php echo isset($error['user_id']) ? $error['user_id'] : ''; ?>
                                    <input type="number" class="form-control" name="user_id" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                               <div class='col-md-10'>
                                    <label for="exampleInputEmail1">Amount</label> <i class="text-danger asterik">*</i><?php echo isset($error['amount']) ? $error['amount'] : ''; ?>
                                    <input type="number" class="form-control" name="amount" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        
                    </div>
                  
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Add</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_branches_form').validate({

        ignore: [],
        debug: false,
        rules: {
            name: "required",
            short_code: "required",

        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>
<script>
    var changeCheckbox = document.querySelector('#trial_earning_button');
    var init = new Switchery(changeCheckbox);
    changeCheckbox.onchange = function() {
        if ($(this).is(':checked')) {
            $('#trial_earnings').val(1);

        } else {
            $('#trial_earnings').val(0);
        }
    };
</script>

<!--code for page clear-->
<script>
    function refreshPage(){
    window.location.reload();
} 
</script>

<?php $db->disconnect(); ?>