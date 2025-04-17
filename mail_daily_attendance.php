<?php
// Include necessary libraries and files
include('ZKLib.php');
include('db.php'); // Include the database connection
require_once 'vendor/autoload.php'; // Include PhpWord library
require_once 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once 'vendor/phpmailer/phpmailer/src/SMTP.php';
require_once 'vendor/phpmailer/phpmailer/src/Exception.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$developers = 'kashifcs509@gmail.com, mit.dgmm@punjab.gov.pk, manageritpunjab@gmail.com, engr.irfan641@gmail.com';


function myMail($body, $overrideTo) {
    $to = 'director.general@mnm.punjab.gov.pk, mansoorjalap@gmail.com, dgmines.gov.pb@gmail.com, padgmm@punjab.gov.pk, kashifcs509@gmail.com, dirmm.gop@gmail.com, ada.dgmm@punjab.gov.pk, arslanhaider73@gmail.com, mit.dgmm@punjab.gov.pk, diradmn.dgmm@punjab.gov.pk, manageritpunjab@gmail.com, engr.irfan641@gmail.com';
    if($overrideTo){
    	$to = $overrideTo;
    }
    $subject = 'Attendance Report for ' . date('d-m-Y');

    // Set up PHPMailer
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
        $mail->Body    = $body;

        // Send email
        $mail->send();
        echo "<p style='text-align: center; color: green;'>Message sent successfully!</p>";
    } catch (Exception $e) {
        echo "<p style='text-align: center; color: red;'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
    }
}

$ret = false;
while($ret==false) { 
    try{
        $zk = new ZKLib('192.168.1.250'); // Your Device IP
        $ret = $zk->connect();
        if (!$ret) {
            throw new Exception('ERROR_DEVICE_CONNECT');
        }
    } catch (Exception $e) {
        myMail($e->$e->getMessage(), '');
    }
}i

// Initialize the ZKTeco device
try{
    $zk = new ZKLib('192.168.1.250'); // Your Device IP
    $ret = $zk->connect();
    print_r($ret);
    exit();
    if (!$ret) {
        throw new Exception('ERROR_DEVICE_CONNECT');
    }
} catch (Exception $e) {
	print_r($e); exit();
	$html = '';
    $html.= '<p>Attendance device is not available.</p>';
    myMail($html);
    echo $html;
    exit();
}


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
    $today2 = date("d-m-Y");

    $html = '';
    $html.= "<div style='width: 90%; margin: 0 auto;'>";
    $html.= "<h1 style='text-align: center; margin-bottom: 20px;'>Directorate General of Mines & Minerals Biometric Attendance Record <br>Dated:$today2</h1>";
    $html.= "<table border='1' cellpadding='5' cellspacing='2' style='width: 100%; text-align: center; border-collapse: collapse;'>";
    $html.= "<tr style='background-color: #f2f2f2;'><th>Serial No</th><th>Name</th><th>Designation</th><th>Date</th><th>Time</th></tr>";

    $serialNo = 1;
    $presentUsers = [];  // Store present users
    if (!empty($attendance)) { // Ensure $attendance is not null
        foreach ($attendance as $attItem) {
            $attDate = date("Y-m-d", strtotime($attItem['timestamp']));
            if ($attDate == $today) {
                $userId = $attItem['id'];
                $userName = isset($users[$userId]['name']) ? $users[$userId]['name'] : 'Unknown';
                $userDesignation = isset($users[$userId]['designation']) ? $users[$userId]['designation'] : 'Unknown';

                $presentUsers[] = $userId;  // Collect present users

                $html.= "<tr>";
                $html.= "<td>{$serialNo}</td>";
                $serialNo++;
               // $html.= "<td>{$userId}</td>";
                $html.= "<td>{$userName}</td>";
                $html.= "<td>{$userDesignation}</td>";
                $html.= "<td>" . date("d-m-Y", strtotime($attItem['timestamp'])) . "</td>";
                $html.= "<td>" . date("H:i:s", strtotime($attItem['timestamp'])) . "</td>";
               // $html.= "<td>" . ZK\Util::getAttType($attItem['type']) . "</td>";
                $html.= "</tr>";
            }
        }
    } else {
        $html.= "<tr><td colspan='7'>No attendance records found for today.</td></tr>";
    }
    $html.= "</table>";

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
        $html.= "<h1 style='text-align: center; margin-top: 30px;'>Directorate General of Mines & Minerals Biometric Late Record<br>Dated:$today2</h1>";
        $html.= "<table border='1' cellpadding='10' style='width: 100%; text-align: center; border-collapse: collapse;'>";
        $html.= "<thead><tr style='background-color: #f2f2f2;'><th>Serial No</th><th>Name</th><th>Designation</th><th>Posting</th></tr></thead>";
        $html.= "<tbody>";

        $serialNo = 1;
        foreach ($filteredAbsentUsers as $userID) {
            $html.= "<tr>";
            $html.= "<td>{$serialNo}</td>";
            $serialNo++;
            //  $html.= "<td>{$userID}</td>";
            $html.= "<td>{$users[$userID]['name']}</td>";
            $html.= "<td>{$users[$userID]['designation']}</td>";
            $html.= "<td>{$users[$userID]['posting']}</td>";
            $html.= "</tr>";
        }
        $html.= "</tbody></table>";
    } else {
        $html.= "<p style='text-align: center; margin-top: 30px;'>No users with 'headquarter' or 'on leave' status are absent today.</p>";
    }

    $html.= "</div>";

    myMail($html);

    // Enable the device and disconnect
    $zk->enableDevice();
    $zk->disconnect();

} else {
    echo "<p style='text-align: center; color: red;'>Could not connect to the device.</p>";
}
?>


