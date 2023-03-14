<?php
	// start session
	ob_start();
	
	session_start();
	// set time for session timeout
	
    // set time for session timeout
    $currentTime = time() + 25200;
    $expired = 900;

    // if current time is more than session timeout back to login page
    if ($currentTime > $_SESSION['timeout']) {
        session_destroy();
        header("location:index.php");
    }
    // destroy previous session timeout and create new one
    unset($_SESSION['timeout']);
    $_SESSION['timeout'] = $currentTime + $expired;
	
	
	
?>

<?php include "header.php";?>
<html>
<head>
<title>Add Notification | Color Challenge</title>
</head>
</body>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
		<?php
			include('public/add-notification-form.php'); 
		?>
      </div><!-- /.content-wrapper -->
  </body>
</html>
<?php include "footer.php";?>
    	