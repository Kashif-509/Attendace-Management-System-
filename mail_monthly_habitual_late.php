<?php
// Include necessary files
include('ZKLib.php');
include('db.php');
require_once 'vendor/autoload.php'; // For PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Define late time
$lateTime = "09:30"; // Fixed late time as 9:30 AM

// Calculate start and end dates for the previous month
$currentMonth = date("Y-m-01"); // First day of current month
$startDate = date("Y-m-01", strtotime("-1 month", strtotime($currentMonth))); // First day of previous month
$endDate = date("Y-m-t", strtotime("-1 month", strtotime($currentMonth)));   // Last day of previous month

// Habitual late threshold
$lateThreshold = 10;

// Initialize ZKTeco device
$zk = new ZKLib('192.168.1.250');
$ret = $zk->connect();
$email_status_message = ""; // Variable to hold email status message

if ($ret) {
    $zk->disableDevice();

    // Fetch users from the database
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

    // Fetch attendance records
    $attendance = $zk->getAttendance();
    $lateUsers = [];

    foreach ($attendance as $attItem) {
        $attDate = date("Y-m-d", strtotime($attItem['timestamp']));
        $attTime = date("H:i", strtotime($attItem['timestamp']));

        // Check if attendance is within the previous month and after the late time limit
        if ($attDate >= $startDate && $attDate <= $endDate && $attTime > $lateTime) {
            $userId = $attItem['id'];
            $userName = $users[$userId]['name'] ?? 'Unknown';
            $userDesignation = $users[$userId]['designation'] ?? 'Unknown';

            // Store each late attendance under the user's ID
            if (!isset($lateUsers[$userId])) { // Initialize user data if not already present
                $lateUsers[$userId] = [
                    'name' => $userName,
                    'designation' => $userDesignation,
                    'late_count' => 0, // Initialize late count
                    'attendance' => []
                ];
            }
            $lateUsers[$userId]['attendance'][] = [
                'date' => $attDate,
                'time' => $attTime
            ];
            $lateUsers[$userId]['late_count']++; // Increment late count for each late entry
        }
    }

    // Filter out users who are not habitually late (less than lateThreshold times)
    $habitualLateUsers = array_filter($lateUsers, function($user) use ($lateThreshold) {
        return $user['late_count'] > $lateThreshold;
    });

    // Sort habitualLateUsers array by late_count in descending order
    uasort($habitualLateUsers, function($a, $b) {
        return $b['late_count'] - $a['late_count']; // Sort descending
    });

    $reportHTML = ""; // To store HTML table for email

    if (!empty($habitualLateUsers)) {
        $reportHTML .= "<!DOCTYPE html><html lang='en'><head><meta charset='utf-8'><title>Habitual Latecomers Report</title><style>body { font-family: 'Nunito', sans-serif; } .table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 0.9em; } .table, .table th, .table td { border: 1px solid #ddd; padding: 5px; text-align: center; } .table th { background-color: #f8f9fa; font-weight: bold; font-size: 13px; } .name-col, .designation-col, .serial-no-col { font-size: 12px; } .late-count-col { font-size: 13px; font-weight: bolder; color: #e74c3c; } .late-entries-container { column-count: 3; text-align: left; font-size: 0.9em; column-gap: 5px; padding-right: 0px; } .late-entry { margin-bottom: 2px; }</style></head><body>"; // Updated CSS with new classes and reduced column-gap/padding-right
        $reportHTML .= "<h1 style='font-size: 1.2em; margin-bottom: 15px;'>Habitual Latecomers Report - " . htmlspecialchars(date('F Y', strtotime('-1 month'))) . "</h1>";
        $reportHTML .= "<table class='table'>";
        $reportHTML .= "<thead><tr><th>Serial No</th><th>Name</th><th>Designation</th><th>Total Late Count</th><th>Late Entries</th></tr></thead>";
        $reportHTML .= "<tbody>";

        $serialNo = 1;
        foreach ($habitualLateUsers as $userId => $userData) {
            $lateEntriesHTML = "<div class='late-entries-container'>"; // Container for 2-column layout
            $entrySerial = 1; // Serial number for late entries within each cell
            foreach ($userData['attendance'] as $entry) {
                $lateEntriesHTML .= "<div class='late-entry'>" . $entrySerial . ". Date: {$entry['date']} - Time: {$entry['time']}</div>"; // Numbered entries
                $entrySerial++;
            }
            $lateEntriesHTML .= "</div>"; // Close container

            $reportHTML .= "<tr>";
            $reportHTML .= "<td class='serial-no-col'>" . $serialNo . "</td>"; // Added class serial-no-col
            $reportHTML .= "<td class='name-col'>" . htmlspecialchars($userData['name']) . "</td>"; // Added class name-col
            $reportHTML .= "<td class='designation-col'>" . htmlspecialchars($userData['designation']) . "</td>"; // Added class designation-col
            $reportHTML .= "<td class='late-count-col'>" . htmlspecialchars($userData['late_count']) . "</td>"; // Added class late-count-col
            $reportHTML .= "<td>" . $lateEntriesHTML . "</td>"; // Use the 2-column HTML
            $reportHTML .= "</tr>";
            $serialNo++;
        }

        $reportHTML .= "</tbody>";
        $reportHTML .= "</table>";
        $reportHTML .= "</body></html>";


    } else {
        $reportHTML = "<p style='font-style: italic;'>No habitual latecomers found for the previous month (".htmlspecialchars($prevMonth).") who were late more than ".htmlspecialchars($lateThreshold)." times after ".htmlspecialchars($lateTime)." AM.</p>"; // Plain text for email body
    }

    // Email sending functionality (no changes here)
    //$to = 'irfanaslam.mmd@gmail.com';
    $to = 'director.general@mnm.punjab.gov.pk, mansoorjalap@gmail.com, dgmines.gov.pb@gmail.com, padgmm@punjab.gov.pk, kashifcs509@gmail.com, dirmm.gop@gmail.com, mit.dgmm@punjab.gov.pk, diradmn.dgmm@punjab.gov.pk, manageritpunjab@gmail.com';

    $subject = 'Habitual Latecomers Report for ' . date('F Y', strtotime('-1 month')); // Subject includes month and year

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'itbranch.mmd@gmail.com';
        $mail->Password = 'lusalailvmjlkqty';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('itbranch.mmd@gmail.com', 'Biometric Attendance System');
        foreach (explode(',', $to) as $recipient) {
            $mail->addAddress(trim($recipient));
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $reportHTML; // Use the generated HTML for the report

        $mail->send();
        $email_status_message = "<p class='email-status email-success'>Email report sent successfully!</p>";

    } catch (Exception $e) {
        $email_status_message = "<p class='email-status email-failure'>Email report could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
    }
    echo $email_status_message; // Display email status on webpage


    // Enable the device and disconnect
    $zk->enableDevice();
    $zk->disconnect();

} else {
    echo "<p class='lead text-danger text-center error-message'>Could not connect to the device.</p>";
}
?>
