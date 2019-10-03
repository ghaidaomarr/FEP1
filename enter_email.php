<?php include('app_logic.php'); ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>forget password</title>
</head>
<body>
  <?php
  session_start();
  require_once './vendor/autoload.php';
  $dotenv = Dotenv\Dotenv::create(__DIR__);
  $dotenv->load();
  // نستطيع الان قراءة المتغيرات من الملف
       
       
  $db_host = getenv('DB_host'); //استدعاء البيانات من الملف المحتوي على البيانات الحساسه
  $db_username = getenv('DB_username');
  $db_password = getenv('DB_password');
  $Database = getenv('DB');
  $connection = mysqli_connect($db_host, $db_username, $db_password, $Database, '8889');
  ?>
    <div id="basicdiv">
        <img id="psauimage" src="images/Prince_Sattam_Bin_Abdulaziz_University.png">
        <img id="fepimage" src="images/FEP.png">
        <hr style="width:400px">
        <div id="inputdiv" style="">
          
        <form class="login-form" action="enter_email.php" method="post">
		<h2 class="form-title">Reset password</h2>
		<!-- form validation messages -->
		<?php include('messages.php'); ?>
		<div class="form-group">
			<label>Your email address</label>
			<input type="email" name="email">
		</div>
		<div class="form-group">
			<button type="submit" name="reset-password" class="login-btn">Submit</button>
		</div>
	</form>
</body>
</html>