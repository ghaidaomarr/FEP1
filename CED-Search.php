<TABLE>
<?php
//include 'connection_database.php';
require_once './vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
// نستطيع الان قراءة المتغيرات من الملف
$db_host = getenv('DB_host'); //استدعاء البيانات من الملف المحتوي على البيانات الحساسه
$db_username = getenv('DB_username');
$db_password = getenv('DB_password');
$Database = getenv('DB');
$conn=new pdo ("mysql:host=$db_host;port=8889;dbname=$Database" , $db_username,$db_password);

try{
if( isset($_POST['TEXT_CED']) )
{
 // إنشاء جدول خاص بالطلاب الذين لديهم اختبارات متتالية في أيام متتابعة  
$CED1="CREATE TEMPORARY TABLE CEDSA
        SELECT e1.Class_ID, e1.Student_ID,
            e1.subject_name as first_subject_name, e1.exam_days as first_exam_day,  e1.exam_dates as First_Exam_Date,
                e2.subject_name as next_subject_name , e2.exam_days as next_exam_day,e2.exam_dates as next_Exam_Date
                    , e1.Student_ID in (
            select B.student_ID from students_away B
        ) as away
                FROM Examdata as e1, Examdata as e2
                WHERE e1.Student_ID = e2.Student_ID AND DATEDIFF(e2.exam_dates,e1.exam_dates) = 1
                ORDER BY Student_ID,First_Exam_Date
                        ";
        $E_CED=$conn->prepare($CED1);
        $E_CED->execute();
        
    
// اختبار البيانات المرسله اذا كانت تطابق اي من البيانات الموجودة في الجدول
    $s=$_POST['TEXT_CED'] ;
    $ss="%$s%";
    $searchced="SELECT * FROM CEDSA  where
    Class_ID like :search   or student_id like :search
    or first_subject_name like  :search   or first_exam_day like   :search or First_Exam_Date like   :search    or
    next_subject_name like :search  or next_exam_day like   :search   or next_Exam_Date   like :search  "; 
    $E_searchced = $conn->prepare($searchced);
      // تنفيذ الإستعلام

$E_searchced->bindparam(':search',$ss,PDO::PARAM_STR);
    $E_searchced->execute();
    $out="";
    if($E_searchced->rowCount()==0){
        $out="No Result";}
    else{
    while ($value = $E_searchced->fetch()) {

// طباعة البيانات المتطابقة وتميز بيانات الطالب البعيد باللون الأصفر
        if ($value['away']==1){
            $out .= "<tr>";
            $out .= '<td> Student ID: ' . $value["Student_ID"] . '</td></tr>';
            $out .= "<tr>";
            $out .= '<td class="SA">' . $value["Class_ID"] . '</td>';
            $out .= '<td class="SA">' . $value["first_subject_name"] . '</td>';
            $out .= '<td class="SA">' . $value["first_exam_day"] .'</td>';
            $out .= '<td class="SA">' . $value["First_Exam_Date"] . '</td>';

            $out .= '<td class="SA">' . $value["next_subject_name"] . '</td>';
            $out .= '<td class="SA">' . $value["next_exam_day"] . '</td>';
            $out .= '<td class="SA">' . $value["next_Exam_Date"] . '</td>';
            $out .= "</tr>";

        }
        else {
            $out .= "<tr>";
            $out .= '<td> Student ID: ' . $value["Student_ID"] . '</td></tr>';
            $out .= "<tr>";
            $out .= '<td class="myDIV">' . $value["Class_ID"] . '</td>';
            $out .= '<td class="myDIV">' . $value["first_subject_name"] . '</td>';
            $out .= '<td class="myDIV">' . $value["first_exam_day"] .'</td>';
            $out .= '<td class="myDIV">' . $value["First_Exam_Date"] . '</td>';

            $out .= '<td class="myDIV">' . $value["next_subject_name"] . '</td>';
            $out .= '<td class="myDIV">' . $value["next_exam_day"] . '</td>';
            $out .= '<td class="myDIV">' . $value["next_Exam_Date"] . '</td>';
            $out .= "</tr>";

        }
    }
}
echo $out;
}
// else{
//     echo "Please Enter To search";
//     }

}
catch(PDOException $catch){
echo "Error : " .$catch->getMessage();
}
?>
</TABLE>