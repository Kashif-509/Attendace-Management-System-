<?php
include('db.php'); // Include the database connection file

// Fetch all users from the database including the posting column
$result = $connection->query("SELECT userid, name, designation, posting FROM users");

if ($result->num_rows > 0) {
    echo "<h1>User List</h1>";
    
    // Create a table structure for displaying users with their designations and posting
    echo "<table border='1' cellpadding='10'>";
    echo "<thead><tr><th>User ID</th><th>Name</th><th>Designation</th><th>Posting</th></tr></thead>";
    echo "<tbody>";

    // Loop through all users and display their data in table rows
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        // Display User ID
        echo "<td>" . htmlspecialchars($row['userid']) . "</td>";
        
        // Display User Name
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        
        // Display User Designation
        echo "<td>" . htmlspecialchars($row['designation']) . "</td>";
        
        // Display User Posting
        echo "<td>" . htmlspecialchars($row['posting']) . "</td>";
        
        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>No users found in the database.</p>";
}

// Close the database connection
$connection->close();
?>
