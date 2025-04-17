<html>
<head>
    <title>ZK Test</title>
</head>

<body>
<?php
    $enableGetDeviceInfo = true;
    $enableGetUsers = true;
    $enableGetData = true;

    include('ZKLib.php');

    // Database connection
    $servername = "localhost"; // Your database server (use 'localhost' for local)
    $username = "root"; // Your MySQL username
    $password = ""; // Your MySQL password
    $dbname = "zk_attendance"; // Database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $zk = new ZKLib(
        '192.168.1.250' // your device IP
    );

    $ret = $zk->connect();
    if ($ret) {
        $zk->disableDevice();
        // $zk->setTime(date('Y-m-d H:i:s')); // Synchronize time
        ?>

        <form method="GET" action="">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>
            <input type="submit" value="Filter">
        </form>

        <hr/>

        <?php if($enableGetDeviceInfo === true) { ?>
        <table border="1" cellpadding="5" cellspacing="2">
            <tr>
                <td><b>Status</b></td>
                <td>Connected</td>
                <td><b>Version</b></td>
                <td><?php echo($zk->version()); ?></td>
                <td><b>OS Version</b></td>
                <td><?php echo($zk->osVersion()); ?></td>
                <td><b>Platform</b></td>
                <td><?php echo($zk->platform()); ?></td>
            </tr>
            <tr>
                <td><b>Firmware Version</b></td>
                <td><?php echo($zk->fmVersion()); ?></td>
                <td><b>WorkCode</b></td>
                <td><?php echo($zk->workCode()); ?></td>
                <td><b>SSR</b></td>
                <td><?php echo($zk->ssr()); ?></td>
                <td><b>Pin Width</b></td>
                <td><?php echo($zk->pinWidth()); ?></td>
            </tr>
            <tr>
                <td><b>Face Function On</b></td>
                <td><?php echo($zk->faceFunctionOn()); ?></td>
                <td><b>Serial Number</b></td>
                <td><?php echo($zk->serialNumber()); ?></td>
                <td><b>Device Name</b></td>
                <td><?php echo($zk->deviceName()); ?></td>
                <td><b>Get Time</b></td>
                <td><?php echo($zk->getTime()); ?></td>
            </tr>
        </table>
        <?php } ?>

        <hr/>
        
        <?php if($enableGetUsers === true) { ?>
        <table border="1" cellpadding="5" cellspacing="2" style="float: left; margin-right: 10px;">
            <tr>
                <th colspan="6">Data User</th>
            </tr>
            <tr>
                <th>UID</th>
                <th>ID</th>
                <th>Name</th>
                <th>Card #</th>
                <th>Role</th>
                <th>Password</th>
            </tr>
            <?php
            try {
                $users = $zk->getUser();
                sleep(1);
                foreach ($users as $uItem) {
                    ?>
                    <tr>
                        <td><?php echo($uItem['uid']); ?></td>
                        <td><?php echo($uItem['userid']); ?></td>
                        <td><?php echo($uItem['name']); ?></td>
                        <td><?php echo($uItem['cardno']); ?></td>
                        <td><?php echo(ZK\Util::getUserRole($uItem['role'])); ?></td>
                        <td><?php echo($uItem['password']); ?>&nbsp;</td>
                    </tr>
                    <?php
                }
            } catch (Exception $e) {
                header("HTTP/1.0 404 Not Found");
                header('HTTP', true, 500); // 500 internal server error
            }
            ?>
        </table>
        <?php } ?>

        <?php if ($enableGetData === true) { ?>
            <table border="1" cellpadding="5" cellspacing="2">
                <tr>
                    <th colspan="7">Data Attendance</th>
                </tr>
                <tr>
                    <th>UID</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>State</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Type</th>
                </tr>
                <?php
                // Get the start and end date from the form input
                if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                    $startDate = $_GET['start_date'];
                    $endDate = $_GET['end_date'];
                    
                    // Get attendance data from the device
                    $attendance = $zk->getAttendance();
                    if (count($attendance) > 0) {
                        $attendance = array_reverse($attendance, true);
                        sleep(1);
                        foreach ($attendance as $attItem) {
                            // Check if the attendance timestamp is within the selected date range
                            $attendanceDate = date("Y-m-d", strtotime($attItem['timestamp']));
                            if ($attendanceDate >= $startDate && $attendanceDate <= $endDate) {
                                $uid = $attItem['uid'];
                                $id = $attItem['id'];
                                $name = isset($users[$attItem['id']]) ? $users[$attItem['id']]['name'] : $attItem['id'];
                                $state = ZK\Util::getAttState($attItem['state']);
                                $timestamp = date("Y-m-d H:i:s", strtotime($attItem['timestamp']));
                                $type = ZK\Util::getAttType($attItem['type']);

                                // Insert data into database
                                $stmt = $conn->prepare("INSERT INTO attendance_data (uid, user_id, name, state, timestamp, type) VALUES (?, ?, ?, ?, ?, ?)");
                                $stmt->bind_param("iissss", $uid, $id, $name, $state, $timestamp, $type);
                                $stmt->execute();

                                ?>
                                <tr>
                                    <td><?php echo($uid); ?></td>
                                    <td><?php echo($id); ?></td>
                                    <td><?php echo($name); ?></td>
                                    <td><?php echo($state); ?></td>
                                    <td><?php echo(date("d-m-Y", strtotime($attItem['timestamp']))); ?></td>
                                    <td><?php echo(date("H:i:s", strtotime($attItem['timestamp']))); ?></td>
                                    <td><?php echo($type); ?></td>
                                </tr>
                                <?php
                            }
                        }
                    }
                }
                ?>
            </table>
        <?php } ?>

        <?php
        $zk->enableDevice();
        $zk->disconnect();
    }

    // Close database connection
    $conn->close();
?>
</body>
</html>








<!-- CREATE DATABASE zk_attendance;

USE zk_attendance;

CREATE TABLE attendance_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uid INT NOT NULL,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    state VARCHAR(50) NOT NULL,
    timestamp DATETIME NOT NULL,
    type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); -->
