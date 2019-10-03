<form method="post" id="SPSSFORM" >

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

if(isset($_POST['SUB']) ){
// إنشاء سليكت لوضع المواد بداخلة  كي يتيح معالجة المواد مرة اخرى 
echo"<select  name='SSUB[]' multiple id='SPSS' >";

$sub=$_POST['SUB'];


foreach ($sub as $selectedsubject){


echo '<option value='.$selectedsubject.'>'.$selectedsubject.'</option>';

}

?>

</select>

<div  style="float:right;margin:0; margin-top:-50px;"id="ssd_div" class="t4">

<input class="button button5" type='submit' name='ssb_se' ID='sb_se' value='processing selected subjects'onclick='myFunction3()'>

</form>
<!-- إنشاء جدول لعرض نتيجة المعالجة  -->

<table class="t1">

<thead>

    <tr>

        <th> Subject ID</th>

        <th> Subject name</th>

        <th>lecturer name</th>

        <th> Previous exam day</th>

        <th> Previous exam date</th>

        <th> Previous student Count</th>

        <th> Updated exam day</th>

        <th> Updated exam date</th>

        <th> Updated student Count</th>

    </tr>

    </thead>

</table>

<div id="ssd_scroll" class="soa"class="t4">

<table class="t5">

</div >

<?php

foreach ($sub as $selectedsubject){

$TSTUDENTSOFSSSD="CREATE TEMPORARY TABLE TSSSSSD 
(Student_ID varchar(255));";

$ETSTUDENTSOFSSSD=mysqli_query($connection,$TSTUDENTSOFSSSD);
// جلب البيانات السابقة للمادة
$Pdataofselectesubject="SELECT DISTINCT Subject_name,lecturer_name,exam_days,exam_dates FROM Examdata where `Subject_ID`='$selectedsubject' ";
$EPdataofselectesubject=mysqli_query($connection,$Pdataofselectesubject);

$FSS=mysqli_fetch_array($EPdataofselectesubject); 

$PEslectedsubject=$FSS['exam_dates'];

echo'<tr>';

    echo '<td>'.$selectedsubject.'</td>';


    echo '<td>'.$FSS['Subject_name'].'</td>';

    echo '<td>'.$FSS['lecturer_name'].'</td>';

    echo '<td>'.$FSS['exam_days'].'</td>';

    echo '<td>'.$PEslectedsubject.'</td>';
    // تحديد جميع طلاب المادة اللي حصل عندها المشكلة 
$STUDENTSOFSSSD="INSERT INTO TSSSSSD SELECT DISTINCT Student_ID From Examdata where `Subject_ID`='$selectedsubject'";

$ESTUDENTSOFSSSD=mysqli_query($connection,$STUDENTSOFSSSD);

    //  تحديد عدد الطلاب الذين كان لديهم المشكله في اليوم السابق (قبل المعالجه)

    $Previous_count="SELECT COUNT(DISTINCT Student_ID)AS PCstudents

    FROM examdata 

    WHERE Student_ID in (SELECT DISTINCT Student_ID FROM TSSSSSD ) AND

    Subject_ID!='$selectedsubject'

    AND exam_dates='$PEslectedsubject'";

        $E_Previous_count=mysqli_query($connection,$Previous_count);

        $F_Previous_count=mysqli_fetch_array($E_Previous_count);

        echo '<td>'.$F_Previous_count['PCstudents'].'</td>';

        //  اختبار عدد الطلاب الذين سيصبح لديهم نفس المشكله لجميع الايام المحدده مسبقا  مع اختيار اقل حاله لذلك

        $dataupdated= "SELECT COUNT(DISTINCT

        CASE

        WHEN d.Student_ID IN ( SELECT Student_ID FROM TSSSSSD  ) 

        AND d.Subject_ID != '$selectedsubject'

        THEN d.Student_ID

        ELSE NULL

        END

    ) AS UCStudents ,d.exam_dates ,d.exam_days

    FROM Examdata d 

    GROUP BY d.exam_dates  ,d.exam_days

    ORDER BY `UCStudents` , d.exam_dates ASC";

        $E_dataupdated=mysqli_query($connection,$dataupdated);

    // احضار البيانات المحدثه التي سيتيم تغير موعد اختبار المادة بناء عليها  

    $F_dataupdated=mysqli_fetch_array($E_dataupdated);

        $updated_count=$F_dataupdated['UCStudents'];

        $updated_day=$F_dataupdated['exam_days'];

        $updated_date=$F_dataupdated['exam_dates'];

        echo"<td>".$updated_day.'</td>';

        echo"<td>".$updated_date.'</td>';

        echo "<td>".$updated_count.'</td>';

        echo'</tr>';

        // تحديث البيانات 

        $E_update="UPDATE Examdata

        SET `exam_dates`='$updated_date' , exam_days='$updated_day' 

        WHERE Subject_ID='$selectedsubject' ";

    $EE_update=mysqli_query($connection,$E_update);

// تفريغ جدول طلاب المشكله عند الماده الحالية حتى يتسنى اضافة طلاب المادة التي تليها 

$DELETE="DELETE FROM TSSSSSD";

$DELETEE=mysqli_query($connection,$DELETE);

    }

}

?>

</table>

</div>

