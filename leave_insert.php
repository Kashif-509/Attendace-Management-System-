<?php 

include('db.php');


 $name=$_POST['username'];
 $leavetype=$_POST['leave_type'];
 $start_date=$_POST['startdate'];
 $end_date=$_POST['enddate'];
 $total_days = $_POST['totaldays'];
 $bio_id =$_POST['bioid'];
 $file_name = $_FILES['file']['name'];
 $file_tmp =$_FILES['file']['tmp_name'];

if(isset($_POST["submit"]))
{ 


if(!empty($_FILES['file']['name']))
{

$fileName = basename($_FILES['file']['name']); 

$fileType=pathinfo($fileName,PATHINFO_EXTENSION); 
         
$allowTypes=array('jpg','png','jpeg','gif'); 
if(in_array($fileType,$allowTypes))
{ 
  //  if ( move_uploaded_file( $_FILES['file']['name'], basename($_FILES['file']['name'] ) ) )
        // {
        if(move_uploaded_file($file_tmp,"images/".$file_name));
           {echo "Success";
            echo " the file has been moved successfuly";}
        // }
    
    // else
    // {
    //     echo " error this file ext is not allowed";
    // }
}
//$image=$_FILES['image']['tmp_name']; 
//$imgContent=addslashes(file_get_contents($image)); 
}

else
{
    echo "I am empty";
}

}
//Insert image content into database 
$insert=$connection->query("INSERT into leaverecord (name,leavetype,start_date,end_date,total_days,bio_id) VALUES ('$name','$leavetype','$start_date','$end_date','$total_days','$bio_id')"); 

//$insert=mysqli_query($connection, "INSERT INTO  `leaverecord`  (name,leavetype,start_date,end_date,total_days,bio_id) VALUES ('" . $name . "', '" . $leavetype. "' ,'" .  $start_date.  "' ,'". $end_date. "' ,'". $total_days. "' ,'". $bio_id."')"); 

//}


//if($insert)
{
$status ='success'; 
$statusMsg = "Query Run."; 
}
/*
else{ 
 $statusMsg = "File upload failed, please try again."; 
 }  
 }else{ 
    $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.'; 
} 
 }else{ 
$statusMsg = 'Please select an image file to upload.';  
} 
} */
//echo $statusMsg; 
?>