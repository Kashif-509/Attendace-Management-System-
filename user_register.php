<?php 

include('db.php');
//include('supermenubar.php');
 
 
 $username=$_POST['username'];
 $designation=$_POST['designation'];
 $biometric_id=$_POST['bio_id'];
 $scale=$_POST['scale'];
if(isset($_POST["submit"]))
{ 

         
//Insert image content into database 
//$insert=$connection->query("INSERT into `public_holidays` (Sr_No,holiday_title,holiday_date,holiday_comment) VALUES ('$Sr_No','$holiday_title','$holiday_date','$holiday_comment')"); 


$insert=mysqli_query($connection, "INSERT INTO  `users` (name,designation,bio_id,scale) VALUES ('" . $username . "', '" . $designation. "' ,'" .  $biometric_id. "'  ,'". $scale. "')"); 

if($insert){ 

$status ="User Registered Sucessfuly"; 
}
echo $status; 
}
?>