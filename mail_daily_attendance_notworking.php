<?php
include('ZKLib.php');
include('db.php');
require_once 'vendor/autoload.php';
require_once 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once 'vendor/phpmailer/phpmailer/src/SMTP.php';
require_once 'vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

define('MAX_RETRIES', 3);
define('TOTAL_ATTEMPTS', 8);
define('RETRY_WAIT', 3600);
define('SMTP_USERNAME', 'itbranch.mmd@gmail.com');
define('SMTP_PASSWORD', 'lusalailvmjlkqty');
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465);
define('DEVICE_IP', '192.168.1.2500');

$developers = 'kashifcs509@gmail.com, mit.dgmm@punjab.gov.pk, manageritpunjab@gmail.com, engr.irfan641@gmail.com';

function sendEmail(string $subject, string $body, string $toList): void {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(SMTP_USERNAME, 'Biometric Attendance System');
        foreach (explode(',', $toList) as $recipient) {
            $mail->addAddress(trim($recipient));
        }
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
    } catch (Exception $e) {
        echo "<p style='text-align: center; color: red;'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
    }
}

function connectToDevice(): bool {
    $zk = new ZKLib(DEVICE_IP);
    for ($attempt = 1; $attempt <= TOTAL_ATTEMPTS; $attempt++) {
        try {
            if ($zk->connect()) {
                return true;
            }
        } catch (Exception $e) {
            sendEmail('Device Connection Error', $e->getMessage(), $developers);
        }
        sleep($attempt * RETRY_WAIT);  // Increase wait time between attempts
    }
    return false;
}

function generateAttendanceReport($zk, $users): string {
    $attendance = $zk->getAttendance();
    $today = date("Y-m-d");
    $todayFormatted = date("d-m-Y");
    $html = "<div style='width: 90%; margin: 0 auto;'>";
    $html .= "<h1 style='text-align: center;'>Biometric Attendance Record - Dated: $todayFormatted</h1>";
    $html .= "<table border='1' cellpadding='5' cellspacing='2' style='width: 100%; text-align: center; border-collapse: collapse;'>";
    $html .= "<tr style='background-color: #f2f2f2;'><th>Serial No</th><th>Name</th><th>Designation</th><th>Date</th><th>Time</th></tr>";
    $serialNo = 1;
    $presentUsers = [];
    foreach ($attendance as $attItem) {
        $attDate = date("Y-m-d", strtotime($attItem['timestamp']));
        if ($attDate == $today) {
            $userId = $attItem['id'];
            $userName = $users[$userId]['name'] ?? 'Unknown';
            $userDesignation = $users[$userId]['designation'] ?? 'Unknown';
            $presentUsers[] = $userId;
            $html .= "<tr>";
            $html .= "<td>{$serialNo}</td>";
            $html .= "<td>{$userName}</td>";
            $html .= "<td>{$userDesignation}</td>";
            $html .= "<td>" . date("d-m-Y", strtotime($attItem['timestamp'])) . "</td>";
            $html .= "<td>" . date("H:i:s", strtotime($attItem['timestamp'])) . "</td>";
            $html .= "</tr>";
            $serialNo++;
        }
    }
    if (empty($presentUsers)) {
        $html .= "<tr><td colspan='5'>No attendance records found for today.</td></tr>";
    }
    $html .= "</table>";
    return $html;
}

function fetchAbsentUsers($users, $presentUsers): array {
    $absentUsers = array_diff(array_keys($users), $presentUsers);
    $filteredAbsentUsers = [];
    foreach ($absentUsers as $userId) {
        $posting = strtolower(trim($users[$userId]['posting']));
        if ($posting === 'headquarter' || $posting === 'on leave') {
            $filteredAbsentUsers[] = $userId;
        }
    }
    return $filteredAbsentUsers;
}

function generateAbsentUserReport($users, $filteredAbsentUsers): string {
    $html = "<h2 style='text-align: center;'>Absent Users (Headquarter/On Leave)</h2>";
    $html .= "<table border='1' cellpadding='5' cellspacing='2' style='width: 100%; text-align: center; border-collapse: collapse;'>";
    $html .= "<thead><tr style='background-color: #f2f2f2;'><th>Serial No</th><th>Name</th><th>Designation</th><th>Posting</th></tr></thead><tbody>";
    $serialNo = 1;
    foreach ($filteredAbsentUsers as $userId) {
        $html .= "<tr>";
        $html .= "<td>{$serialNo}</td>";
        $html .= "<td>{$users[$userId]['name']}</td>";
        $html .= "<td>{$users[$userId]['designation']}</td>";
        $html .= "<td>{$users[$userId]['posting']}</td>";
        $html .= "</tr>";
        $serialNo++;
    }
    $html .= "</tbody></table>";
    return $html;
}

$zk = null;
if (connectToDevice()) {
    $zk = new ZKLib(DEVICE_IP);
    $zk->disableDevice();
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
    $attendanceReport = generateAttendanceReport($zk, $users);
    $filteredAbsentUsers = fetchAbsentUsers($users, array_keys($users));
    $absentUsersReport = generateAbsentUserReport($users, $filteredAbsentUsers);
    sendEmail('Attendance Report for ' . date('d-m-Y'), $attendanceReport . $absentUsersReport, $developers);
    $zk->enableDevice();
    $zk->disconnect();
} else {
    echo "<p style='text-align: center; color: red;'>Could not connect to the biometric device.</p>";
}

?>$zk = null;

