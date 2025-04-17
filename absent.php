<?php
include('db.php'); // Include the database connection file
include('ZKLib.php'); // Include ZKLib for biometric attendance

$zk = new ZKLib('192.168.1.250'); // Your Device IP

$ret = $zk->connect();
if ($ret) {
    $zk->disableDevice();

    // Fetch all users from the database including the posting column
    $result = $connection->query("SELECT userid, name, designation, posting FROM users");

    // Array to hold user data (user ID, name, designation, and posting)
    $allUsers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $allUsers[$row['userid']] = [
                'name' => $row['name'],
                'designation' => $row['designation'],
                'posting' => $row['posting']
            ];
        }
    }

    // Get today's attendance from the biometric device
    $attendance = $zk->getAttendance();
    $today = date("Y-m-d"); // Get today's date

    // Array to hold user IDs who have marked attendance today
    $presentUsers = [];
    if (count($attendance) > 0) {
        foreach ($attendance as $attItem) {
            $attDate = date("Y-m-d", strtotime($attItem['timestamp'])); // Extract date from timestamp
            if ($attDate == $today) {
                $presentUsers[] = $attItem['id'];
            }
        }
    }

    // Find absent users by comparing all users with present users
    $absentUsers = array_diff(array_keys($allUsers), $presentUsers);

    // Filter absent users to only include those with posting of 'headquarter' or 'on leave'
    $filteredAbsentUsers = [];
    foreach ($absentUsers as $userID) {
        // Clean and convert the posting value to lowercase, accounting for extra spaces
        $posting = strtolower(trim($allUsers[$userID]['posting']));
        
        if ($posting === 'headquarter' || $posting === 'on leave' || $posting === 'head quarter') {
            $filteredAbsentUsers[] = $userID;
        }
    }

    // Display the filtered absent users along with their posting
    if (count($filteredAbsentUsers) > 0) {
        echo "<h1>Absent Users for Today</h1>";
        echo "<table border='1' cellpadding='10'>";
        echo "<thead><tr><th>Serial No</th><th>User ID</th><th>Name</th><th>Designation</th><th>Posting</th></tr></thead>";
        echo "<tbody>";

        // Initialize the serial number
        $serialNo = 1;

        foreach ($filteredAbsentUsers as $userID) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($serialNo++) . "</td>"; // Serial number column
            echo "<td>" . htmlspecialchars($userID) . "</td>";
            echo "<td>" . htmlspecialchars($allUsers[$userID]['name']) . "</td>";
            echo "<td>" . htmlspecialchars($allUsers[$userID]['designation']) . "</td>";
            echo "<td>" . htmlspecialchars($allUsers[$userID]['posting']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p>No users with 'headquarter' or 'on leave' status are absent today.</p>";
    }

    $zk->enableDevice();
    $zk->disconnect();
} else {
    echo "<p>Failed to connect to the biometric device.</p>";
}

// Close the database connection
$connection->close();
?>
