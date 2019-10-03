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
  if (count($errors) > 0) : ?>
  <div class="msg">
  	<?php foreach ($errors as $error) : ?>
  	  <span><?php echo $error ?></span>
  	<?php endforeach ?>
  </div>
<?php  endif ?>