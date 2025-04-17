<?php
include('db.php'); // Include the database connection file

// Function to capitalize every word in the string
function capitalizeEveryWord($string) {
    return ucwords(strtolower($string));
}

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted user ID, name, designation, and posting from the form
    $userid = $_POST['userid'];
    $new_name = capitalizeEveryWord($_POST['name']);
    $new_designation = capitalizeEveryWord($_POST['designation']);
    $new_posting = capitalizeEveryWord($_POST['posting']);

    // Prepare and execute the SQL statement to update the user's name, designation, and posting
    $stmt = $connection->prepare("UPDATE users SET name = ?, designation = ?, posting = ? WHERE userid = ?");
    $stmt->bind_param("ssss", $new_name, $new_designation, $new_posting, $userid);
    if ($stmt->execute()) {
        echo "<p>User with ID " . htmlspecialchars($userid) . " has been updated to " . htmlspecialchars($new_name) . " with designation " . htmlspecialchars($new_designation) . " and posting " . htmlspecialchars($new_posting) . ".</p>";
    } else {
        echo "<p>Failed to update user with ID " . htmlspecialchars($userid) . ".</p>";
    }
    $stmt->close();
}

// Fetch all users from the database
$result = $connection->query("SELECT userid, name, designation, posting FROM users");

if ($result->num_rows > 0) {
    echo "<h1>Edit Users</h1>";
    
    // Create a table structure for better organization
    echo "<table border='1' cellpadding='10'>";
    echo "<thead><tr><th>User ID</th><th>Name</th><th>Designation</th><th>Posting</th><th>Action</th></tr></thead>";
    echo "<tbody>";

    // Loop through all users and display their data in table rows, each with its own form
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        // Display User ID in one column
        echo "<td>" . htmlspecialchars($row['userid']) . "</td>";
        
        // Display a form for each user with input fields for name, designation, and posting
        echo "<td>";
        echo "<form method='POST' action='edit_user.php'>";
        echo "<input type='hidden' name='userid' value='{$row['userid']}'>";
        echo "<input type='text' name='name' value='" . htmlspecialchars($row['name']) . "' required>";
        echo "</td>";
        
        // Display input field for Designation
        echo "<td>";
        echo "<input type='text' name='designation' value='" . htmlspecialchars($row['designation']) . "' required>";
        echo "</td>";
        
        // Display input field for Posting (check if 'posting' exists in the result set)
        echo "<td>";
        $postingValue = isset($row['posting']) ? htmlspecialchars($row['posting']) : ''; // Default to empty string if undefined
        echo "<input type='text' name='posting' value='" . $postingValue . "' required>";
        echo "</td>";
        
        // Submit button in the last column
        echo "<td>";
        echo "<input type='submit' value='Update User'>";
        echo "</td>";
        echo "</form>"; // Close the form for this user

        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "<p>No users found in the database.</p>";
}

// Close the database connection
$connection->close();
?>
