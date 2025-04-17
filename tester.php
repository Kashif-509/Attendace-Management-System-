<?php
// Include libraries
require('../phpqrcode/qrlib.php');
require('../class.phpmailer.php');
require('../class.smtp.php');
require('../phpword.php');
require('../vendor/autoload.php');
require('../zklib/ZKLib.php');
require('../db/connection.php');

use Dompdf\Dompdf;
use PhpOffice\PhpWord\PhpWord;

// === Constants ===
define('DEVICE_IP', '192.168.1.250');
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', 'itbranch.mmd@gmail.com');
define('SMTP_PASSWORD', 'lusalailvmjlkqty');
define('SMTP_PORT', 465);
define('EMAIL_FROM', 'itbranch.mmd@gmail.com');
define('EMAIL_FROM_NAME', 'Attendance System');
define('LOG_FILE', __DIR__ . '/logs/error_log.txt');

// === Recipients ===
$to = [
    'director.general@mnm.punjab.gov.pk',
    'mansoorjalap@gmail.com',
    'dgmines.gov.pb@gmail.com',
    'padgmm@punjab.gov.pk',
    'kashifcs509@gmail.com',
    'dirmm.gop@gmail.com',
    'ada.dgmm@punjab.gov.pk',
    'arslanhaider73@gmail.com',
    'mit.dgmm@punjab.gov.pk',
    'diradmn.dgmm@punjab.gov.pk',
    'manageritpunjab@gmail.com',
    'engr.irfan641@gmail.com'
];

$developers = [
    'kashifcs509@gmail.com',
    'mit.dgmm@punjab.gov.pk',
    'manageritpunjab@gmail.com',
    'engr.irfan641@gmail.com'
];

// === Logging ===
function logError($msg)
{
    file_put_contents(LOG_FILE, "[" . date('Y-m-d H:i:s') . "] $msg\n", FILE_APPEND);
}

// === Email Sender ===
function sendEmail($subject, $body, $recipients, $attachments = [])
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = SMTP_PORT;

        $mail->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        foreach ($recipients as $r)
            $mail->addAddress($r);
        foreach ($attachments as $a)
            $mail->addAttachment($a);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        logError("Email failed: " . $e->getMessage());
    }
}

// === Connect to ZK Device with Retry Logic ===
function connectToDeviceWithRetry()
{
    $zk = new ZKLib(DEVICE_IP);
    $maxRetries = 5;
    $attempt = 0;
    while ($attempt < $maxRetries) {
        try {
            if ($zk->connect()) {
                return $zk;  // Successfully connected, return the ZK object
            }
        } catch (Exception $e) {
            logError("Attempt " . ($attempt + 1) . " failed: " . $e->getMessage());
        }
        $attempt++;
        sleep(3);  // Delay of 3 seconds before retrying
    }
    return false;  // If connection fails after 5 attempts, return false
}

// === Check Duplicate Entry ===
function isDuplicate($con, $code, $datetime)
{
    $sql = "SELECT id FROM attendance WHERE code = '$code' AND datetime = '$datetime'";
    return mysqli_num_rows(mysqli_query($con, $sql)) > 0;
}

// === Save PDF ===
function saveAsPDF($html)
{
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->render();
    $file = __DIR__ . "/reports/attendance_" . date("Ymd_His") . ".pdf";
    file_put_contents($file, $dompdf->output());
    return $file;
}

// === Save Word ===
function saveAsWord($html)
{
    $phpWord = new PhpWord();
    $section = $phpWord->addSection();
    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);
    $file = __DIR__ . "/reports/attendance_" . date("Ymd_His") . ".docx";
    $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $writer->save($file);
    return $file;
}

// === Attendance Sync ===
function processAttendance($zk, $connection)
{
    $zk->disableDevice();
    $attendance = $zk->getAttendance();
    $zk->enableDevice();
    $zk->disconnect();

    $html = '<h2>Attendance Report</h2><table border="1" cellpadding="5"><tr><th>SN</th><th>Name</th><th>Code</th><th>Date</th><th>Time</th></tr>';
    $sn = 1;
    $syncedCount = 0;

    foreach ($attendance as $att) {
        $uid = $att['id'];
        $datetime = $att['timestamp'];
        $date = date('Y-m-d', strtotime($datetime));
        $time = date('H:i:s', strtotime($datetime));

        if (isDuplicate($connection, $uid, $datetime))
            continue;

        $sql = "SELECT name, code FROM employees WHERE code = '$uid'";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'] ?? 'Unknown';
        $code = $row['code'] ?? $uid;

        $insert = "INSERT INTO attendance (code, name, datetime) VALUES ('$code', '$name', '$datetime')";
        if (mysqli_query($connection, $insert)) {
            logError("✅ Synced: $name ($code) at $datetime");
            $html .= "<tr><td>$sn</td><td>$name</td><td>$code</td><td>$date</td><td>$time</td></tr>";
            $sn++;
            $syncedCount++;
        } else {
            logError("❌ Failed to insert: $name ($code) at $datetime");
        }
    }

    $html .= "</table>";

    if ($syncedCount > 0) {
        $pdfPath = saveAsPDF($html);
        $wordPath = saveAsWord($html);

        sendEmail("📋 Attendance Synced - " . date("Y-m-d H:i:s"), $html, $GLOBALS['to'], [$pdfPath, $wordPath]);
    } else {
        logError("ℹ️ No new attendance records to sync.");
    }
}

// === MAIN EXECUTION ===
// Triggered manually, so no time check here.
$zk = connectToDeviceWithRetry();
if ($zk) {
    processAttendance($zk, $connection);
} else {
    $error = "❌ Failed to connect to ZK device after multiple attempts.";
    logError($error);
    sendEmail("Connection Error", "<p>$error</p>", $developers);
}
?>