<?php
require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
// نستطيع الان قراءة المتغيرات من الملف
$db_host = getenv('DB_host'); //استدعاء البيانات من الملف المحتوي على البيانات الحساسه
$db_username = getenv('DB_username');
$db_password = getenv('DB_password');
$Database = getenv('DB');
$connection = mysqli_connect($db_host, $db_username, $db_password, $Database,'8889');

if (isset($_POST["Download"])) {
//get records from database

$query = $connection->query("SELECT * FROM Examdata ORDER BY id DESC");

if($query->num_rows > 0){
    $delimiter = ",";
    $filename = "Examsfile_After_Optimization_" . date('Y-m-d') . ".csv";
     //create a file pointer
    $f = fopen('php://memory', 'w');
      //set column headers
    $fields = array('Class_ID', 'Subject_ID', 'Student_ID', 'Subject_name','lecturer_name','exam_days', 'exam_dates','exam_times');
    fputcsv($f, $fields, $delimiter);
     //output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){
        $lineData = array( $row['Class_ID'], $row['Subject_ID'], $row['Student_ID'], $row['Subject_name'],$row['lecturer_name'] ,$row['exam_days'], $row['exam_dates'], $row['exam_times']);
        fputcsv($f, $lineData, $delimiter);
    }
     //move back to beginning of file
    fseek($f, 0);
    //set headers to download file rather than displayed
   // header('Content-Encoding: UTF-8');
    header('Content-type: text/csv; charset=UTF-8');
   // header('Content-Disposition: attachment; filename=Customers_Export.csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    echo "\xEF\xBB\xBF"; // UTF-8 BOM
    //output all remaining data on a file pointer
    fpassthru($f);
}
exit;
}
?>