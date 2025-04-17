<?php
//include_once 'db.php';

include('db.php');  
//include('adminsession.php');



// Attempt select query execution
//$sql = "SELECT * FROM presentrecord";
$counter = 1;
$date ="2023-09-25";
//$sql = "SELECT  distinct presentrecord.ac,presentrecord.emp_name,presentrecord.emp_designation,presentrecord.month_date,presentrecord.att_time  FROM presentrecord
//INNER JOIN user_info ON presentrecord.ac= ( SELECT ac FROM user_info where id= $loggedin_id)";

//$sql="SELECT COUNT(ac) FROM presentrecord where ac= 1";

//echo "<div class='search-box'>
//<input type='text' autocomplete='off' placeholder='Search Name...' />
//<div class='result'>
//</div>";
//echo "<div>" 
?>
<!--
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}); 
</script>
</head>  -->
<!--input type="text" id="myinput" onclick="myFunction()" placeholder="Search for names.."-->





<body>

<!--  Present Record Data    -->

    <h3 id='title'> Directorate General of Mines & Minerals Biometric Attendance Record </h3>
    <div id="tablediv">
<table class='myTable'  style="width:100%">

<thead>

            <tr>
              <th>Serial No</th>
                <th>Employee Name</th>
                <th>Employee Designation</th>
              
                <th>Attendance Time</th>
                <th>Attendance Mark</th>
                
            <!--th>&nbsp Date</th-->
               <!--th>id</th-->
            </tr>
            </thead>
           
            <?php
//for ($i = 1; $i < 160; $i++) 


   // $sql = ("SELECT * FROM  presentrecord where ac=$i");
   //$result=$connection->query("SELECT distinct image FROM leaverecord INNER JOIN user_info ON leaverecord.ac= ( SELECT ac FROM user_info where ac= '$ac')"); 
  // $sql1 = ("SELECT * FROM  attendace_report where Attendance_Date= '2023-08-29'");
//   $sql1="SELECT users.name, users.designation, attendace_report.Attendance_Time,attendace_report.Attendance_Date
//   FROM users, attendace_report
//   WHERE  users.bio_id = attendace_report.bio_id";

$sql1="SELECT users.name, users.designation,users.Scale, attendace_report.Attendance_Time,attendace_report.Attendance_Date
  FROM users
  JOIN attendace_report on users.bio_id = attendace_report.bio_id
  WHERE DATE(attendace_report.Attendance_Date)='$date' ORDER BY attendace_report.Attendance_Time ASC ";
 
//$sql2= "SELECT name, leavetype,start_date,end_date from leaverecord where end_date >= $date";

    //echo $sql; users.bio_id = attendace_report.bio_id
    //echo "$sql";
//$sql = "SELECT ac FROM user_info where id= $loggedin_id";
//$sql = "SELECT * FROM presentrecord where $sql1 AND $sql";
    //echo "<div id='st'></div>";
   // echo "<div class='st1'><a href='leaveretrieve.php' class='st1-btn'>Check Leave Record</a></div>";
   // echo "<div class='st1'><a href='absentrecord.php' class='st1-btn'>Check Absent Record</a></div>";


    
   // echo "<div class='st2'>";
    //echo "<tr>";     first change
    //echo "<td>" . $counter. "</td>";    second change
   
    if ($result = mysqli_query($connection, $sql1)) 
    {
       
       

        if (mysqli_num_rows($result) > 0) 
        {
            //$row = mysqli_fetch_array($result)
            
       while ($row = mysqli_fetch_array($result))
     {
         ?>
           <tbody id="mybody">
            <!-- Here table id is defined  -->
                <tr>
                <td><?php echo  $counter?> </td>
                <td><?php echo  $row['name']?> </td>
                <td><?php echo  $row['designation']?> </td>
               
                <td><?php echo  $row['Attendance_Time']?> </td>
               
                <td><?php echo  "Present"?> </td>
                </tr>

               
                <?php 
        
       
             ?>

             <?php 

                

$counter++;
           
        } //end of if
      
        
            
        }    //end of while
        
      //else

       {   ?>
      <!--  No record found here -->
     
     <?php  }



    }   
      ?>
</tr>
</tbody>
</table>

    </div>


<!--  Leave Record Data    -->


  </br> 



    <h3 id='title'> Directorate General of Mines & Minerals Biometric Attendance Leave Record </h3>
    <div id="tablediv">
<table class='myTable'  style="width:100%">

<thead>

            <tr>
              <th>Serial No</th>
                <th>Employee Name</th>
                <th>Employee Designation</th>
                <th>Leave Type</th>
                <th>Leave Date</th>
                <th>Attendance Status</th>
                
            <!--th>&nbsp Date</th-->
               <!--th>id</th-->
            </tr>
            </thead>
           
            <?php
//for ($i = 1; $i < 160; $i++) 


   // $sql = ("SELECT * FROM  presentrecord where ac=$i");
   //$result=$connection->query("SELECT distinct image FROM leaverecord INNER JOIN user_info ON leaverecord.ac= ( SELECT ac FROM user_info where ac= '$ac')"); 
  // $sql1 = ("SELECT * FROM  attendace_report where Attendance_Date= '2023-08-29'");
//   $sql1="SELECT users.name, users.designation, attendace_report.Attendance_Time,attendace_report.Attendance_Date
//   FROM users, attendace_report
//   WHERE  users.bio_id = attendace_report.bio_id";


 
$sql2="SELECT users.name, users.designation,leaverecord.end_date,leaverecord.leavetype
  FROM users
  JOIN leaverecord on users.bio_id = leaverecord.bio_id
  WHERE  (end_date >= '$date')";

//$sql2= "SELECT name, leavetype,start_date,end_date from leaverecord where (end_date >= '$date')";

    //echo $sql; users.bio_id = attendace_report.bio_id
    //echo "$sql";
//$sql = "SELECT ac FROM user_info where id= $loggedin_id";
//$sql = "SELECT * FROM presentrecord where $sql1 AND $sql";
    //echo "<div id='st'></div>";
   // echo "<div class='st1'><a href='leaveretrieve.php' class='st1-btn'>Check Leave Record</a></div>";
   // echo "<div class='st1'><a href='absentrecord.php' class='st1-btn'>Check Absent Record</a></div>";


    
   // echo "<div class='st2'>";
    //echo "<tr>";     first change
    //echo "<td>" . $counter. "</td>";    second change
   
    if ($result1 = mysqli_query($connection, $sql2)) 
    {
       
       

        if (mysqli_num_rows($result1) > 0) 
        {
            //$row = mysqli_fetch_array($result)
            
       while ($row = mysqli_fetch_array($result1))
     {
         ?>
           <tbody id="mybody">
            <!-- Here table id is defined  -->
                <tr>
                <td><?php echo  $counter?> </td>
                <td><?php echo  $row['name']?> </td>
                <td><?php echo  $row['designation']?> </td>
                <td><?php echo  $row['leavetype']?> </td>
                <td><?php echo  $row['end_date']?> </td>
               
                
               
                <td><?php echo  "Leave"?> </td>
                </tr>

               
                <?php 
        
       
             ?>

             <?php 

                

$counter++;
           
        } //end of if
      
        
            
        }    //end of while
        
      //else

       {   ?>
      <!--  No record found here -->
     
     <?php  }



    }   
      ?>
</tr>
</tbody>
</table>

    </div>









<!--  Absent Record Data    -->

</br> 









<h3 id='title'> Directorate General of Mines & Minerals Biometric Attendance Absent Record </h3>
<div id="tablediv">
<table class='myTable'  style="width:100%">

<thead>

        <tr>
          <th>Serial No</th>
            <th>Employee Name</th>
            <th>Employee Designation</th>
            <th>Employee Biometric id</th>
            
            
        <!--th>&nbsp Date</th-->
           <!--th>id</th-->
        </tr>
        </thead>
       
        <?php
//for ($i = 1; $i < 160; $i++) 


// $sql = ("SELECT * FROM  presentrecord where ac=$i");
//$result=$connection->query("SELECT distinct image FROM leaverecord INNER JOIN user_info ON leaverecord.ac= ( SELECT ac FROM user_info where ac= '$ac')"); 
// $sql1 = ("SELECT * FROM  attendace_report where Attendance_Date= '2023-08-29'");
//   $sql1="SELECT users.name, users.designation, attendace_report.Attendance_Time,attendace_report.Attendance_Date
//   FROM users, attendace_report
//   WHERE  users.bio_id = attendace_report.bio_id";



// $sql3="SELECT bio_id FROM users
// MINUS
// SELECT bio_id FROM leaverecord (end_date >= '$date')";

 $sql3=   "SELECT users.bio_id,users.name,users.designation from users except (SELECT users.bio_id, users.name,users.designation
 FROM  users 
 LEFT JOIN attendace_report  ON attendace_report.bio_id = users.bio_id 
 LEFT JOIN leaverecord  ON leaverecord.bio_id = users.bio_id
 WHERE attendace_report.Attendance_Date = '2023-09-25')" ;


 //WHERE attendace_report.bio_id IS NULL"
 
 //WHERE attendace_report.Attendance_Date = '2023-09-25' ";
 
 //LEFT JOIN leaverecord  ON users.bio_id = leaverecord.bio_id
//echo $sql; users.bio_id = attendace_report.bio_id
//echo "$sql";
//$sql = "SELECT ac FROM user_info where id= $loggedin_id";
//$sql = "SELECT * FROM presentrecord where $sql1 AND $sql";
//echo "<div id='st'></div>";
// echo "<div class='st1'><a href='leaveretrieve.php' class='st1-btn'>Check Leave Record</a></div>";
// echo "<div class='st1'><a href='absentrecord.php' class='st1-btn'>Check Absent Record</a></div>";



 echo "<div class='st2'>";
echo "<tr>";    
echo "<td>" . $counter. "</td>";    

if ($result2 = mysqli_query($connection, $sql3)) 
{
   
   

    if (mysqli_num_rows($result2) > 0) 
    {
        //$row = mysqli_fetch_array($result)
        
   while ($row = mysqli_fetch_array($result2))
 {
     ?>
        <tbody id="mybody">
        <!-- Here table id is defined  -->
            <tr>
            <td><?php echo  $counter?> </td>
            <td><?php echo  $row['name']?> </td>
            <td><?php echo  $row['designation']?> </td>
           <td><?php echo  $row['bio_id']?> </td>
           
           
           
             </tr>

           
            <?php 
    
   
         ?>

         <?php 

            

 $counter++;
       
    } //end of if
  
        
    }    //end of while
    
 //else

   {   ?>
   <!--  No record found here -->
 
  <!-- <?php  } 



}   
  ?>
 </tr>
 </tbody>
 </table>

 </div>

<?php  mysqli_close($connection);  ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});



</script>

</body>

<!--footer id="footer">
	 
		<div class="footer2">
			<div class="container">
				<div class="row">

					<div class="col-md-6 panel">
						<div class="panel-body">
							<p class="simplenav">
								
							</p>
						</div>
					</div>

					<div class="col-md-6 panel">
						<div class="panel-body">
							<p class="text-right">
								Copyright &copy; 2023.  by <a href="http://minesandminerals.online/" rel="develop">Directorate General of Mines and Minerals</a>
							</p>
						</div>
					</div>

				</div>
				 /row of panels >
			</div>
		</div>
	</footer-->


<!--script type="text/javascript" src="report.js"></script-->



<style>
      #title
      {
        text-align: center;
      }
   
        .myTable {
          /* width: 300px; */
            border-collapse: collapse;
            width: 32%;
        }
        .myTable, th, td {
            border: 1px solid black;
           /* padding-top: 1px; */
           
        }
        th, td {
           
           
            text-align: center;
        }
    </style>




