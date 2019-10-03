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
$errors = [];
if (isset($_POST['reset-password'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    // ensure that the user exists on our system
    $query = "SELECT Email FROM Admin WHERE Email='$email'";
    $results = mysqli_query($connection, $query);
  
    if (empty($email)) {
      array_push($errors, "Your email is required");
    }else if(mysqli_num_rows($results) <= 0) {
      array_push($errors, "Sorry, no user exists on our system with that email");
    }
    // generate a unique random token of length 100
    $token = bin2hex(random_bytes(50));
  
    if (count($errors) == 0) {
      // store token in the password-reset database table against the user's email
      $sql = "INSERT INTO password_reset(email, token) VALUES ('$email', '$token')";
      $results = mysqli_query($connection, $sql);
  
      // Send email to user with the token in a link they can click on
      $to = $email;
      $subject = "Reset your password on";
      $msg = "Hi there, click on this <a href=\"new_pass.php?token=" . $token . "\">link</a> to reset your password on our site";
      $msg = wordwrap($msg,70);
      $headers = "From: BillGates@microsoft.com";
      mail($to, $subject, $msg, $headers);
      header('location: pending.php?email=' . $email);
    }
  }
  
  // ENTER A NEW PASSWORD
  if (isset($_POST['new_password'])) {
    $new_pass = mysqli_real_escape_string($connection, $_POST['new_pass']);
    $new_pass_c = mysqli_real_escape_string($connection, $_POST['new_pass_c']);
  
    // Grab to token that came from the email link
    $token = $_SESSION['token'];
    if (empty($new_pass) || empty($new_pass_c)) array_push($errors, "Password is required");
    if ($new_pass !== $new_pass_c) array_push($errors, "Password do not match");
    if (count($errors) == 0) {
      // select email address of user from the password_reset table 
      $sql = "SELECT email FROM password_reset WHERE token='$token' LIMIT 1";
      $results = mysqli_query($connection, $sql);
      $email = mysqli_fetch_assoc($results)['email'];
  
      if ($email) {
        $new_pass = md5($new_pass);
        $sql = "UPDATE Admin SET password='$new_pass' WHERE Email='$email'";
        $results = mysqli_query($connection, $sql);
        header('location: index.php');
      }
    }
  }
  ?>