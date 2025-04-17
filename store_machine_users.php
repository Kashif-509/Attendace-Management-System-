<?php
require(__DIR__ . '/zklib/vendor/autoload.php');
include('ZKLib.php');
include('db.php'); // Include the database connection file

// Load the ZKLib class
$ip = '192.168.1.250';  // Replace with your ZKTeco device's IP
$port = 4370;
$zk = new ZKLib($ip, $port);

// Function to capitalize every word in the string
function capitalizeEveryWord($string) {
    return ucwords(strtolower($string));
}

// Connect to the device
if ($zk->connect()) {
    echo "<h2>Connected to ZKTeco device</h2>";

    // Fetch registered users
    $users = $zk->getUser();

    // Convert user IDs to integers for proper sorting
    usort($users, function($a, $b) {
        return intval($a['userid']) - intval($b['userid']);
    });

    if (!empty($users)) {
        // Start HTML table structure
        echo "<html><head><title>Daily User Report - " . date('Y-m-d') . "</title></head><body>";
        echo "<h1>Daily User Report for " . date('Y-m-d') . "</h1>";
        echo "<table border='1' cellpadding='10'>";
        echo "<thead><tr><th>User ID</th><th>Name</th><th>Card Number</th><th>UID</th><th>Role</th><th>Password</th></tr></thead>";
        echo "<tbody>";

        // Loop through each user and display data in table rows
        foreach ($users as $user) {
            // Display data in table rows
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['userid']) . "</td>";
            echo "<td>" . htmlspecialchars(capitalizeEveryWord($user['name'])) . "</td>";
            echo "<td>" . htmlspecialchars($user['cardno']) . "</td>";
            echo "<td>" . htmlspecialchars($user['uid']) . "</td>";
            echo "<td>" . htmlspecialchars($user['role']) . "</td>";
            echo "<td>" . htmlspecialchars($user['password']) . "</td>";
            echo "</tr>";

            // Check if the user already exists in the database
            $checkStmt = $connection->prepare("SELECT userid FROM users WHERE userid = ?");
            $checkStmt->bind_param("s", $user['userid']);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows == 0) {
                // If the user doesn't exist, insert into the database
                $stmt = $connection->prepare("INSERT INTO users (userid, name) VALUES (?, ?)");
                $stmt->bind_param("ss", $user['userid'], capitalizeEveryWord($user['name']));
                $stmt->execute();
                $stmt->close();
            } else {
                echo "<p>User with ID " . htmlspecialchars($user['userid']) . " already exists. Skipping insert.</p>";
            }

            $checkStmt->close();
        }

        // End of table and HTML structure
        echo "</tbody></table>";
        echo "</body></html>";

    } else {
        echo "<p>No users found on the device.</p>";
    }

    // Disconnect from the device
    $zk->disconnect();
} else {
    echo "<p>Failed to connect to the ZKTeco device.</p>";
}

// Close database connection
$connection->close();
?>
