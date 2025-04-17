<?php 

include('db.php');
//include('supermenubar.php');
 
 $Sr_No =1;
 $holiday_title=$_POST['holidaytitle'];
 $holiday_date=$_POST['holidaydate'];
 $holiday_comment=$_POST['holidaycomment'];
 
if(isset($_POST["submit"]))
{ 

         
//Insert image content into database 
//$insert=$connection->query("INSERT into `public_holidays` (Sr_No,holiday_title,holiday_date,holiday_comment) VALUES ('$Sr_No','$holiday_title','$holiday_date','$holiday_comment')"); 


$insert=mysqli_query($connection, "INSERT INTO  `public_holidays` (Sr_No,holiday_title,holiday_date,holiday_comment) VALUES ('" . $Sr_No . "', '" . $holiday_title . "' ,'" . $holiday_date . "' ,'" . $holiday_comment. "')"); 

if($insert){ 

$status ="Public Holiday Added Sucessfuly"; 
}
echo $status; 
}
?>