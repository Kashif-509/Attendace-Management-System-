<?php
    $attendance = $zk->getAttendance();
    if (count($attendance) > 0) {
        $attendance = array_reverse($attendance, true);
        sleep(1);
        foreach ($attendance as $attItem) {
            ?>
            <tr>
                <td><?php echo($attItem['uid']); ?></td>
                <td><?php echo($attItem['id']); ?></td>
                <td><?php echo(isset($users[$attItem['id']]) ? $users[$attItem['id']]['name'] : $attItem['id']); ?></td>
                <td><?php echo(ZK\Util::getAttState($attItem['state'])); ?></td>
                <td><?php echo(date("d-m-Y", strtotime($attItem['timestamp']))); ?></td>
                <td><?php echo(date("H:i:s", strtotime($attItem['timestamp']))); ?></td>
                <td><?php echo(ZK\Util::getAttType($attItem['type'])); ?></td>
            </tr>
            <?php
        }
    }
?>