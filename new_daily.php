<html>
<head>
    <title>MinesandMineralsLMS</title>
</head>

<body>
<?php
    $enableGetDeviceInfo = true;
    $enableGetUsers = true;
    $enableGetData = true;

    include('ZKLib.php');
    include('db.php'); // Include the database connection

    $zk = new ZKLib('192.168.1.250'); // Your Device IP

    $ret = $zk->connect();
    if ($ret) {
        $zk->disableDevice();
        ?>
        <hr/>
        <?php if ($enableGetUsers === true) { 
            // Fetch all users from the database and store them in an associative array
            $usersQuery = "SELECT userid, name, designation FROM users"; // Fetch name and designation
            $usersResult = mysqli_query($connection, $usersQuery);

            $users = [];
            if ($usersResult && mysqli_num_rows($usersResult) > 0) {
                while ($row = mysqli_fetch_assoc($usersResult)) {
                    // Store the user data by userid
                    $users[$row['userid']] = [
                        'name' => $row['name'],
                        'designation' => $row['designation']
                    ];
                }
            }
        ?>
        <?php } ?>
        <?php if ($enableGetData === true) { ?>
            <table border="1" cellpadding="5" cellspacing="2">
                <tr>
                    <th colspan="7">Today's Attendance</th>
                </tr>
                <tr>
                    <th>Serial No</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Designation</th> <!-- Replace "State" with "Designation" -->
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                </tr>
                <!-- Data attendance start -->
                <?php
                   $attendance = $zk->getAttendance();
                   $today = date("Y-m-d"); // Get today's date
                   
                   if (count($attendance) > 0) {
                       $serialNo = 1; // Initialize serial number
                   
                       foreach ($attendance as $attItem) {
                           $attDate = date("Y-m-d", strtotime($attItem['timestamp'])); // Extract date from timestamp
                           if ($attDate == $today) {
                               $userId = $attItem['id'];
                               // Fetch name and designation from the users array
                               $userName = isset($users[$userId]['name']) ? $users[$userId]['name'] : 'Unknown';
                               $userDesignation = isset($users[$userId]['designation']) ? $users[$userId]['designation'] : 'Unknown';
                               ?>
                               <tr>
                                   <td><?php echo($serialNo++); ?></td> <!-- Serial number column -->
                                   <td><?php echo($userId); ?></td>
                                   <td><?php echo($userName); ?></td> <!-- Display name from users table -->
                                   <td><?php echo($userDesignation); ?></td> <!-- Display designation from users table -->
                                   <td><?php echo(date("d-m-Y", strtotime($attItem['timestamp']))); ?></td>
                                   <td><?php echo(date("H:i:s", strtotime($attItem['timestamp']))); ?></td>
                                   <td><?php echo(ZK\Util::getAttType($attItem['type'])); ?></td>
                               </tr>
                               <?php
                           }
                       }
                   } else {
                       echo "<tr><td colspan='7'>No attendance records found for today.</td></tr>";
                   }
                ?>
                <!-- Data attendance end -->
            </table>
        <?php } ?>
        <?php
        $zk->enableDevice();
        $zk->disconnect();
    }
?>
</body>
</html>
