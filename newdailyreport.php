<?php
// Include necessary libraries and files
include('ZKLib.php');
include('db.php'); // Include the database connection
require_once 'vendor/autoload.php'; // Include PhpWord library

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

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

    
    $phpWord = new PhpWord();
    $section = $phpWord->addSection();
    
    // Add paragraph style for centered text
    $phpWord->addParagraphStyle(
        'centered',
        array('alignment' => \PhpOffice\PhpWord\SimpleType\TextAlignment::CENTER)
    );
    
    // Add title for Present List (centered)
    $section->addText('Present Employees for Today', null, 'centered');
    
    // Add Present List table with reduced borders, margins, and cell size
    $tableStyle = [
        'borderSize' => 6,
        'borderColor' => '000000',
        'cellMargin' => 10, // Reduced margin
        'cellSpacing' => 0  // No spacing between cells
    ];
    
    // Cell style for compact table with reduced padding and centered content
    $cellStyle = [
        'valign' => 'center',
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        'width' => 800 // Adjust width for compact size
    ];
    
    // Font style for compact size and line spacing
    $fontStyle = [
        'size' => 11,  // Smaller font size
        'name' => 'TimesNewRoman'
    ];
    
    // Paragraph style to remove space above/below text
    $paragraphStyle = [
        'spaceBefore' => 0,
        'spaceAfter' => 0,
        'lineHeight' => 1.0 // Compact line height
    ];
    
    // Apply the table style
    $phpWord->addTableStyle('PresentTable', $tableStyle);
    $presentTable = $section->addTable('PresentTable');
    
    // Add header row
    $presentTable->addRow();
    $presentTable->addCell(null, $cellStyle)->addText('Serial No', $fontStyle, $paragraphStyle);
    $presentTable->addCell(null, $cellStyle)->addText('ID', $fontStyle, $paragraphStyle);
    $presentTable->addCell(null, $cellStyle)->addText('Name', $fontStyle, $paragraphStyle);
    $presentTable->addCell(null, $cellStyle)->addText('Designation', $fontStyle, $paragraphStyle);
    $presentTable->addCell(null, $cellStyle)->addText('Date', $fontStyle, $paragraphStyle);
    $presentTable->addCell(null, $cellStyle)->addText('Time', $fontStyle, $paragraphStyle);
    $presentTable->addCell(null, $cellStyle)->addText('Type', $fontStyle, $paragraphStyle);
    
    // Loop through present users and add them to the table
    $serialNo = 1;
    foreach ($attendance as $attItem) {
        $userId = $attItem['id'];
        $userName = isset($users[$userId]['name']) ? $users[$userId]['name'] : 'Unknown';
        $userDesignation = isset($users[$userId]['designation']) ? $users[$userId]['designation'] : 'Unknown';
        $attDate = date("Y-m-d", strtotime($attItem['timestamp']));
    
        if ($attDate == $today) {
            $presentTable->addRow();
            $presentTable->addCell(null, $cellStyle)->addText($serialNo++, $fontStyle, $paragraphStyle);
            $presentTable->addCell(null, $cellStyle)->addText($userId, $fontStyle, $paragraphStyle);
            $presentTable->addCell(null, $cellStyle)->addText($userName, $fontStyle, $paragraphStyle);
            $presentTable->addCell(null, $cellStyle)->addText($userDesignation, $fontStyle, $paragraphStyle);
            $presentTable->addCell(null, $cellStyle)->addText(date("d-m-Y", strtotime($attItem['timestamp'])), $fontStyle, $paragraphStyle);
            $presentTable->addCell(null, $cellStyle)->addText(date("H:i:s", strtotime($attItem['timestamp'])), $fontStyle, $paragraphStyle);
            $presentTable->addCell(null, $cellStyle)->addText(ZK\Util::getAttType($attItem['type']), $fontStyle, $paragraphStyle);
        }
    }
    
    // Add title for Absent List (centered)
    $section->addText('Absent Employees for Today', null, 'centered');
    
    // Add Absent List table with the same compact style
    $phpWord->addTableStyle('AbsentTable', $tableStyle);
    $absentTable = $section->addTable('AbsentTable');
    
    // Add header row
    $absentTable->addRow();
    $absentTable->addCell(null, $cellStyle)->addText('Serial No', $fontStyle, $paragraphStyle);
    $absentTable->addCell(null, $cellStyle)->addText('User ID', $fontStyle, $paragraphStyle);
    $absentTable->addCell(null, $cellStyle)->addText('Name', $fontStyle, $paragraphStyle);
    $absentTable->addCell(null, $cellStyle)->addText('Designation', $fontStyle, $paragraphStyle);
    $absentTable->addCell(null, $cellStyle)->addText('Posting', $fontStyle, $paragraphStyle);
    
    // Loop through absent users and add them to the table
    $serialNo = 1;
    foreach ($filteredAbsentUsers as $userID) {
        $absentTable->addRow();
        $absentTable->addCell(null, $cellStyle)->addText($serialNo++, $fontStyle, $paragraphStyle);
        $absentTable->addCell(null, $cellStyle)->addText($userID, $fontStyle, $paragraphStyle);
        $absentTable->addCell(null, $cellStyle)->addText($users[$userID]['name'], $fontStyle, $paragraphStyle);
        $absentTable->addCell(null, $cellStyle)->addText($users[$userID]['designation'], $fontStyle, $paragraphStyle);
        $absentTable->addCell(null, $cellStyle)->addText($users[$userID]['posting'], $fontStyle, $paragraphStyle);
    }
    
    // Save Word document
    $docName = 'attendance_report.docx';
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($docName);
    
    exit;
    



} else {
    echo "Could not connect to the device.";
}
?>
