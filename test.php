<?php
//include_once 'db.php';

include('db.php');  
//include('adminsession.php');



// Attempt select query execution
//$sql = "SELECT * FROM presentrecord";
$counter = 1;
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
<table class='myTable' >
<table>
<thead>
            <tr>
              <th>Serial No</th>
                <th>Employee Name</th>
                <th>Employee Designation</th>
                <th>Attendance Time</th>
                <th>Leave Mark</th>
                
            <!--th>&nbsp Date</th-->
               <!--th>id</th-->
            </tr>
            </thead>
           
            <?php
//for ($i = 1; $i < 160; $i++) 
{

   // $sql = ("SELECT * FROM  presentrecord where ac=$i");
   $sql1 = ("SELECT * FROM  attendace_report where Attendance_Date= '2023-08-29'");
    
 

    //echo $sql;
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
           <tbody id="myTable">
            <!-- Here table id is defined  -->
                <tr>
                <td><?php echo  $counter?> </td>
                <td><?php echo  $row['Username']?> </td>
                <td><?php echo  $row['Attendance_Time']?> </td>
                <td><?php echo  $row['User_State']?> </td>
                <td><?php echo "Present"?> </td>
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
}       ?>
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

<footer id="footer">
	 
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
				<!-- /row of panels -->
			</div>
		</div>
	</footer>


<!--script type="text/javascript" src="report.js"></script-->
<style>

.myTable{
  
  /* margin: 30px 300px 40px 300px;
 padding: 0px 20px 0px 0px;*/
  line-height: 30px;
  font-family: Helventia;
  /*width: 60%;*/
  
  margin: auto;
}
.myTable {
  
  margin-top: 30px;
}
th, td {
  text-align: center;
  padding: 8px;
  border: 3px solid #ddd;
  /*min-width: 7%;*/
  white-space: nowrap;
  width: 10%;
}


tr:hover {
  background-color: #ebfbe0;
}
/*tr:nth-child(even){background-color: #04AA6D} */
th {
  background-color: #d2f7b7;
  color: #554545;
  font-weight: 600;
  font-family: Times;
}
th, td {
  text-align: center;
  padding: 0.9em;
  border: 2px solid #ffe7d1;
  min-width: 7%;
  white-space: nowrap;
  width: 10%;
  font-family: Time;
  line-height: 2em;
}

a {
  color: #b7484d;
  text-decoration: none;
}

a:hover, a:focus {
  color: #b7484d;
}

#myInput {
  background-color: #f8ffe047;
  color: #020000;
  border: 2px solid #4CAF50;
  border-radius: 8px;
  padding: 6px 3px 7px 7px;
  font-family: Times;
  text-align: ;
  margin: 1px;
}

#myInput:hover:active:focus

{
  background-color: #f8ffe047;
  color: #020000;
  border: 2px solid #4CAF50;
  border-radius: 8px;
  padding: 6px 3px 7px 7px;
  font-family: Times;
  text-align: ;
  margin: 1px;
}
/*tr:nth-child(even) {
 // background-color: #dddddd;
} */
tbody tr:nth-child(odd+1){
  background-color: pink;
  color: #fff;
}
#menubar
{
    text-align: center;
    margin-bottom: 0px;
    color: cornflowerblue;
}
#headingreport
{
    text-align: center;
    padding-top: 120px;
    color: cornflowerblue;
}
#st
{
    background-color: blueviolet;
    height: 100px;
}

.st1{
	width:100%;
	text-align:center;
	padding-top:30px;
	padding-bottom:10px;
}

.st1-btn{
	text-align:center;
	padding:10px 21px 10px 21px;
	background-color:cornflowerblue;
	border:none;
	color:#fff;
	cursor:pointer;
	font-size:20px;
	font-weight:bold;
    border-radius: 12px;;
}

.st1-btn:hover {
  color: #d5d5fd !important;
}
.st2
{
    width: 100%;
    margin: auto;
}
.text-right {
  text-align: left;
}
</style>





