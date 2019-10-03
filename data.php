<?php
session_start();
if (!isset($_SESSION['userid'])){
 
    header("location: index.php");
}
?>

<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<link rel="stylesheet" type="text/css" href="data.css">
<script src="jquery-3.4.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="data.js" > </script>
</head>
<!-- UPload File -->
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
$connection = mysqli_connect($db_host, $db_username, $db_password, $Database,'8889');
$queryempty = 'select * From Examdata';
$query = mysqli_query($connection, $queryempty) or die("Error in query: $query. " . mysqli_error());
if (mysqli_num_rows($query) > 0) {
    $message = "There is data";
} else {
    if (isset($_POST["import"])) {
        $fileName = $_FILES["file"]["tmp_name"];
        if ($_FILES["file"]["size"] > 0) {
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 110000, ",")) !== false) {
                $sqlInsert = "INSERT into Examdata (Class_ID,Subject_ID,Student_ID,Subject_name,lecturer_name,exam_days,exam_dates,exam_times)
                values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[5] . "','" . $column[6] . "','" . $column[7] . "')";
                $result = mysqli_query($connection, $sqlInsert);
                if (!empty($result)) {
                    //$addid="ALTER TABLE `examdata` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
                    //";
                    //$Eaddid=mysqli_query($connection,$addid);
                    $message = "Upload Done ";
                } else {
                    $message = "Problem In Upload Data";
                }
            }
            $dataclean="DELETE n1 FROM Examdata n1, Examdata n2 WHERE n1.id > n2.id AND n1.`Class_ID` = n2.`Class_ID` AND n1.`Subject_ID` = n2.`Subject_ID` AND n1.`Student_ID` = n2.`Student_ID` AND n1.`Subject_name` = n2.`Subject_name` AND n1.`lecturer_name` = n2.`lecturer_name`AND n1.`exam_days` = n2.`exam_days` AND n1.`exam_dates` = n2.`exam_dates` and n1.`exam_times` = n2.`exam_times`";
            $datacleaning=mysqli_query($connection,$dataclean);
        }
    }
}
if (isset($_POST['delete'])) {
//     $deleteid="ALTER TABLE `Examdata`
//     DROP `id`;
//   ";
//     $Edeleteid=mysqli_query($connection,$deleteid);
    $Bedit=mysqli_query($connection,"UPDATE buttons 
    SET PSSD=0
    WHERE PSSD=1");

    $sqldelete = "delete from Examdata";
    $result1 = mysqli_query($connection, $sqldelete);
    if (!empty($result1)) {
        $message = "Deleted Done";
    }
}
?>
<body id="body">
<div id="bar">
<div id="wlcomeadmin">Welcome Admin
</div>
<label>
<a id="bar_img">
    <img width="55%" src="images/Prince_Sattam_Bin_Abdulaziz_University.png"></a>
    </label>
</div>
<div id="uploadfile">
<br>
<div id="outer-scontainer">
<div id="uploaddiv">
<div id="response"><?php if (!empty($message)) {echo $message;}?></div>
    <div class="row">
            <form class="form-horizontal" action="" method="post"
            name="frmCSVImport" id="frmCSVImport" enctype="multipart/form-data">
                <div class="input-row">
                    <label>
                        <input type="file" name="file"id="file" class="file1"accept=".csv">
                        <img src="images/upload-2.png"id="file"><br>
                    </label>
                    <br>
                    <table id="tablebtn">
                    <tr> 
                    <td><label class="col-md-4 control-label">Choose CSV File to Upload </label></td>
                    <td><input  style="display:none"type='submit'id="import" name='import' class="import"value='Import'></td>
                    </tr>
                    <tr>
                    <td> <label> Delete The Current Data </label> </td>
                    <td> <input type="submit"value="Delete"name="delete" id="delete"></td>
                    </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!--End upload file-->
<?php
$queryempty2 = 'select * From students_away';
$query2 = mysqli_query($connection, $queryempty2) or die("Error in query: $query2. " . mysqli_error());
if (mysqli_num_rows($query2) > 0) {
    $message2 = "There is data";
} else {
    if (isset($_POST["import2"])) {
        $fileName = $_FILES["file"]["tmp_name"];
        if ($_FILES["file"]["size"] > 0) {
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 110000, ",")) !== false) {
                $sqlInsert1 = "INSERT into students_away (Student_ID,town,distance,department)
                values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "')";
                $resultw = mysqli_query($connection, $sqlInsert1);
                if (!empty($resultw)) {
                    //$addid="ALTER TABLE `examdata` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
                    //";
                    //$Eaddid=mysqli_query($connection,$addid);
                    $message2 = "Upload Done ";
                } else {
                    $message2 = "Problem In Upload Data";
                }
            }
            $dataclean2="DELETE n1 FROM students_away n1, students_away n2 WHERE n1.id > n2.id AND n1.`Student_ID` = n2.`Student_ID`";
            $datacleaning2=mysqli_query($connection,$dataclean2);
        }
    }
}
if (isset($_POST['delete2'])) {


    $sqldelete2 = "delete from students_away";
    $result2 = mysqli_query($connection, $sqldelete2);
    if (!empty($result2)) {
        $message2 = "Deleted Done";
    }
}
?>
<div id="uploadfile2">
<br>
<div id="outer-scontainer">
<div id="uploaddiv">
<div id="response"><?php if (!empty($message2)) {echo $message2;}?></div>
    <div class="row">
            <form class="form-horizontal" action="" method="post"
            name="frmCSVImport2" id="frmCSVImport2" enctype="multipart/form-data">
                <div class="input-row">
                    <label>
                        <input type="file" name="file"id="file" class="file1"accept=".csv">
                        <img src="images/upload-2.png"id="file"><br>
                    </label>
                    <br>
                    <table id="tablebtn">
                    <tr> 
                    <td><label class="col-md-4 control-label">Choose CSV File to Upload </label></td>
                    <td><input  style="display:none"type='submit'id="import" name='import2' class="import"value='Import'></td>
                    </tr>
                    <tr>
                    <td> <label> Delete The Current Data </label> </td>
                    <td> <input type="submit"value="Delete"name="delete2" id="delete"></td>
                    </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- SSD->student in same day-->
<div id="ssd_div" >
<form method="POST">
<div id="search_div">
<h3 id="ssd_title"> Student Who Have Exames In The Same Day</h3>
<div id="inpout">
<input type="text"id="search" name="search" style="height:25px;border-radius: 25px;margin:40px;">
<input type="submit"class="hide" id="Search-btn"name="btn"value="Search">
</div>
</div>
</form> 
<table id="Mainـhead">
<thead>
<tr>
    <th width="8%">Class ID</th>
    <th width="4%">Subject_ID</th>
    <th width="20%">Subject_name</th>
    <th width="10%">lecturer_name</th>
    <th width="10%">exam_days</th>
    <th width="8%">exam_dates</th>
    <th width="10%">exam_dates</th>
</tr>
<thead>
</table>
<div id="ssd_scroll">
<div id="SSD_search" >
</div>
<div class="SSD-tables">
<?php
$SSD_sql = $connection->query("SELECT * from Examdata join (SELECT Student_ID, count(*), exam_dates FROM Examdata 
group by Student_ID, exam_dates
having count(*) > 1) Examdata1 on Examdata.Student_ID=Examdata1.Student_ID 
and Examdata1.exam_dates=Examdata.exam_dates 
order by Examdata1.Student_ID,Examdata1.exam_dates ;")->fetch_all(MYSQLI_ASSOC);
$student = "";
foreach ($SSD_sql as $sq) {
    if ($student != $sq["Student_ID"]) {
        $student = $sq["Student_ID"];
        echo '<table id="ssd_student"  class="panel"> 
        <tr>
        <td width="70px">
        <label>
            <input type="submit" class="' . $student . '" style="onclick="Function();"" id="SSD-detiles">
            <img src="images/up-arrow.png"id="img_arrow">
            <br>
        </label>
        </td>';
        echo '<td> Student ID: ' . $sq["Student_ID"] . '</td></tr>';
    }
    echo '<tr id="ssd_data" class="'. $student . '" >';
    echo '<td class="myDIV">' . $sq["Class_ID"] . '</td>';
    echo '<td class="myDIV">' . $sq["Subject_ID"] . '</td>';
    echo '<td class="myDIV">' . $sq["Subject_name"] . '</td>';
    echo '<td class="myDIV">' . $sq["lecturer_name"] . '</td>';
    echo '<td class="myDIV">' . $sq["exam_days"] . '</td>';
    echo '<td class="myDIV">' . $sq["exam_dates"] . '</td>';
    echo '<td class="myDIV">' . $sq["exam_times"] . '</td></tr>';
}
?>
</table>
</div>
</div>
<div id="nextprev">
<label id="nextlabel">
    <a id="next" >
    <img  width="7%" src="images/prev.png"></a>
</label>
<label>
<a id="prev">
    <img width="7%" src="images/next.png"></a>
</label>
</div>
</div>
<!-- end SSD->student in same day-->


<!--suggest new day of SSD -->
<div  style="float:right;margin:0; margin-top:-600px;"id="ssd_div" class="t4">
<div id="SuggestNewDay">
<p class="StyleFont">Processing The Problem Of Students Who Have More Than One Exam In The Same Day</p>
<!--class="ButtonMoreInformation ButtonMoreInformation1"-->
<?php
$Bcheck=mysqli_query($connection,"SELECT * from buttons ");
$EBcheck=mysqli_fetch_array($Bcheck);
if($EBcheck['PSSD']==1){
    echo'<div class="warning-msg">
    <img src="images/i-important (1).png">
  <i class="fa fa-warning"></i>
  <sup>hed been processed data before ..</sup>
</div>';
}
?>
<!--هنا جزء الرساله المنبثقه التابعه ل بوتون الانفرميشن-->
<button id="myBtn" class="button button5"  >More Information</button>
<div id="myModal" class="modal">
  <div class="modal-content">
    <div class="modal-body">
<h2 id="h2">
<center>
    Process of students problem policy <br>-those who have more than one exam  on the same day-
</center>
</h2>
<p id="p">
<font color=" #ff0e0e"><B> Note:</B></font> If you press on “ processing ” the data will change which means the data will never be as what it was. 
When you press “processing” for the first time all the data will be processed after that a list of choices 
will appear contains the subject ID, in case you want to process any of those subjects again, all you have to do is selecting 
all the subjects you want then press “processing selected subjects” beside the selected subject.  
However, pressing “processing” button only the subjects of the students who have this issue will be processed.
</p>
    </div>
    <span class="close">Close</span></h3>
    </div>
</div>
<script>
// Get the modal
var modal = document.getElementById('myModal');
// Get the button that opens the modal
var btn = document.getElementById("myBtn");
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
// When the user clicks on the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<!--نهاية بوتون الانفرميشن-->
<form method="POST" id="proform">
<input class="button button5" type="submit" name="processing" id="processing" value="processing" onclick="myFunction()">
</form>
<form method="post" action="download.php">
<input type="submit"class="button button5"  value="Download" name="Download">
</form>
<img src="images\loading.gif" style="margin-left:35%;display:none;"id="load-img" >
<div id="PSSD_INFO"> </div>
</div > </div>

<!-- end suggest new day SSD -->


<!-- من هنا -->
<!-- CED ->consecutive exams in consecutive days   -->

 <div id="CED_box">
<div id="search_div">
<h3 id="CED_title"> Student Who Have Consecutive Exames In Consecutive Day</h3>
<BR>
<form method="POST">
<div id="inpout">
<input type="text" name="tosearch1" id="textbox-CED" style="height:25px;border-radius: 25px;margin:23.5px;" > 
<input type="submit" value ="search" name ="search" id="search-CED"class="hide-CED" >

</div>
</div>
<br>
</form> 
<table id="Mainـhead">
<thead>
<tr ID="HE">
    <th width="12.5%">Subject ID</th>

    <th width="12.5%">First Subject Name</th>
    <th width="12.5%">Lecturer    name</th>
    <th width="12.5%">First Exam    Day</th>
    <th width="12.5%">First Exam    Date</th>
    <th width="12.5%">Next  Subject Name</th>
    <th width="12.5%">lecturer    name</th>
    <th width="12.5%">Next  Exam    Day</th>
    <th width="12.5%">Next  Exam    Date</th>
</tr>
<thead>
</table>
<div id="CED_scroll">
<div id="CED_search" >
</div>
<div class="CED-tables">

<?php
try {
     $conn=new pdo ("mysql:host=$db_host;port=8889;dbname=$Database" , $db_username,$db_password);
//البحث عن الطلاب الذين لديهم اختبارات متتالية في أيام متتابعة 
    $CEDSA="SELECT e1.Subject_ID as  first_Subject_ID, e1.Student_ID,
    e1.subject_name as  first_subject_name,e1.lecturer_name as  first_lecturer_name,e1.exam_days as first_exam_day,  e1.exam_dates as First_Exam_Date,
        e2.subject_name as next_subject_name ,e2.Subject_ID as next_Subject_ID,e2.lecturer_name as next_lecturer_name , e2.exam_days as next_exam_day,e2.exam_dates as next_Exam_Date
        , e1.Student_ID in (
    select B.student_ID from students_away B
) as away
    FROM Examdata as e1, Examdata as e2
    WHERE e1.Student_ID = e2.Student_ID AND DATEDIFF(e2.exam_dates,e1.exam_dates) = 1
    ORDER BY Student_ID,First_Exam_Date";
    $CED_sql = $conn->prepare($CEDSA);
    // تنفيذ الإستعلام
$CED_sql->execute();
$student_CED = "";
// طباعة سطر بيانات الطالب من دون تكرار طباعة الاي-دي الخاص به
foreach ($CED_sql as $cq) {
    if ($student_CED != $cq["Student_ID"]) {
        $student_CED = $cq["Student_ID"];
        echo '<table id="CED_student"  class="panel"> 
        <tr>
        <td width="70px">
        <label>
            <input type="submit" class="' . $student_CED . '"  id="CED-detiles">
            <img src="images/up-arrow.png"id="img_arrow">
            <br>
        </label>
        </td>';
        echo '<td> Student ID: ' . $cq["Student_ID"] . '</td></tr>';
    }
// أختبار إذا كان الطالب بعيد عن الجامعة وتميز سطر بياناته باللون الأصفر
    if ($cq['away']==0){

    echo '<tr id="CED_data" class="'. $student_CED . '" >';
    echo '<td class="myDIV">' . $cq["first_Subject_ID"] . '</td>';
    echo '<td class="myDIV">' . $cq["first_subject_name"] . '</td>';
    echo '<td class="myDIV">' . $cq["first_lecturer_name"] . '</td>';
    echo '<td class="myDIV">' . $cq["first_exam_day"] . '</td>';
    echo '<td class="myDIV">' . $cq["First_Exam_Date"] . '</td>';
    
    echo '<td class="myDIV">' . $cq["next_Subject_ID"] . '</td>';
    echo '<td class="myDIV">' . $cq["next_subject_name"] . '</td>';
    echo '<td class="myDIV">' . $cq["next_lecturer_name"] . '</td>';
    echo '<td class="myDIV">' . $cq["next_exam_day"] . '</td>';
    echo '<td class="myDIV">' . $cq["next_Exam_Date"] . '</td></tr>';
}
else{
    echo '<tr id="CED_data" class="'. $student_CED . '" >';
    echo '<td class="SA">' . $cq["first_Subject_ID"] . '</td>';
    echo '<td class="SA">' . $cq["first_subject_name"] . '</td>';
    echo '<td class="SA">' . $cq["first_lecturer_name"] . '</td>';
    echo '<td class="SA">' . $cq["first_exam_day"] . '</td>';
    echo '<td class="SA">' . $cq["First_Exam_Date"] . '</td>';
    
    echo '<td class="SA">' . $cq["next_Subject_ID"] . '</td>';
    echo '<td class="SA">' . $cq["next_subject_name"] . '</td>';
    echo '<td class="SA">' . $cq["next_lecturer_name"] . '</td>';
    echo '<td class="SA">' . $cq["next_exam_day"] . '</td>';
    echo '<td class="SA">' . $cq["next_Exam_Date"] . '</td></tr>';
}
}
}


catch(PDOException $catch){

    echo "Sorry no connection : " .$catch->getMessage();
}

?>
</table>
</div>
</div>
<img src="images/i-important (1).png" id="SAN" >
<h3 id="note">
    Yellow row for the student with distance away from the university
</h3>

<div id="nextprev">
<label id="nextlabel">
    <a id="next-CED" >
    <img  width="7%" src="images/prev.png" ></a>
</label>
<label>
<a id="prev-CED">
    <img width="7%" src="images/next.png"></a>
</label>
</div>
</div> 


<!--  end  CED -->

<!-- الى هنا  -->