<?php
// Run the script only if accessed via a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('ZKLib.php');
    include('db.php'); // Include the database connection
    require_once 'vendor/autoload.php'; // Include PhpWord library


    // Initialize the ZKTeco device
    $zk = new ZKLib('192.168.1.250'); // Your Device IP
    $ret = $zk->connect();

    if ($ret) {
        $zk->disableDevice();

        // Fetch all users from the database and store them in an associative array
        $usersQuery = "SELECT userid, name, designation, posting FROM users"; 
        $usersResult = mysqli_query($connection, $usersQuery);

        $users = [];
        if ($usersResult && mysqli_num_rows($usersResult) > 0) {
            while ($row = mysqli_fetch_assoc($usersResult)) {
                $users[$row['userid']] = [
                    'name' => $row['name'],
                    'designation' => $row['designation'],
                    'posting' => $row['posting']
                ];
            }
        }

        // Fetch attendance from the device
        $attendance = $zk->getAttendance();
        $today = date("Y-m-d"); 

        // Display today's attendance
        echo "<h1 style='text-align: center;'>Today's Attendance</h1>";
        echo "<table border='1' cellpadding='5' cellspacing='2' style='width: 100%; text-align: center;'>";
        echo "<tr><th>Serial No</th><th>ID</th><th>Name</th><th>Designation</th><th>Date</th><th>Time</th><th>Type</th></tr>";
        
        $serialNo = 1;
        $presentUsers = [];  // Store present users
        if (count($attendance) > 0) {
            foreach ($attendance as $attItem) {
                $attDate = date("Y-m-d", strtotime($attItem['timestamp']));
                if ($attDate == $today) {
                    $userId = $attItem['id'];
                    $userName = isset($users[$userId]['name']) ? $users[$userId]['name'] : 'Unknown';
                    $userDesignation = isset($users[$userId]['designation']) ? $users[$userId]['designation'] : 'Unknown';

                    $presentUsers[] = $userId;  // Collect present users

                    echo "<tr>";
                    echo "<td>{$serialNo}</td>";
                    $serialNo++;
                    echo "<td>{$userId}</td>";
                    echo "<td>{$userName}</td>";
                    echo "<td>{$userDesignation}</td>";
                    echo "<td>" . date("d-m-Y", strtotime($attItem['timestamp'])) . "</td>";
                    echo "<td>" . date("H:i:s", strtotime($attItem['timestamp'])) . "</td>";
                    echo "<td>" . ZK\Util::getAttType($attItem['type']) . "</td>";
                    echo "</tr>";
                }
            }
        } else {
            echo "<tr><td colspan='7'>No attendance records found for today.</td></tr>";
        }
        echo "</table>";

        // Calculate absent users
        $absentUsers = array_diff(array_keys($users), $presentUsers);
        $filteredAbsentUsers = [];
        foreach ($absentUsers as $userID) {
            $posting = strtolower(trim($users[$userID]['posting']));
            if ($posting === 'headquarter' || $posting === 'on leave' || $posting === 'head quarter') {
                $filteredAbsentUsers[] = $userID;
            }
        }

        // Display absent users with specific posting status
        if (!empty($filteredAbsentUsers)) {
            echo "<h1 style='text-align: center;'>Absent Users for Today</h1>";
            echo "<table border='1' cellpadding='10' style='width: 100%; text-align: center;'>";
            echo "<thead><tr><th>Serial No</th><th>User ID</th><th>Name</th><th>Designation</th><th>Posting</th></tr></thead>";
            echo "<tbody>";

            $serialNo = 1;
            foreach ($filteredAbsentUsers as $userID) {
                echo "<tr>";
                echo "<td>{$serialNo}</td>";
                $serialNo++;
                echo "<td>{$userID}</td>";
                echo "<td>{$users[$userID]['name']}</td>";
                echo "<td>{$users[$userID]['designation']}</td>";
                echo "<td>{$users[$userID]['posting']}</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No users with 'headquarter' or 'on leave' status are absent today.</p>";
        }

        // Enable the device and disconnect
        $zk->enableDevice();
        $zk->disconnect();
    }
    else{
        echo "Could not connect to device";
    }
} else {
    echo "<p>Unauthorized access. Please return to the <a href='home.php'>home page</a> and generate the report from there.</p>";
}
