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
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Password Reset PHP</title>
	<link rel="stylesheet" href="main.css">
</head>
<body>
	<form class="login-form" action="new_pass.php" method="post">
		<h2 class="form-title">New password</h2>
		<!-- form validation messages -->
		<?php include('messages.php'); ?>
		<div class="form-group">
			<label>New password</label>
			<input type="password" name="new_pass">
		</div>
		<div class="form-group">
			<label>Confirm new password</label>
			<input type="password" name="new_pass_c">
		</div>
		<div class="form-group">
			<button type="submit" name="new_password" class="login-btn">Submit</button>
		</div>
	</form>
</body>
</html>