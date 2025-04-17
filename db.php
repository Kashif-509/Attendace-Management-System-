<?php

        $host = "localhost";
        $dbusername = "admin";
        $dbpassword = "Mines003@@";
        $dbname = "attendance_report";
        $connection = new mysqli($host, $dbusername, $dbpassword, $dbname);
        // Check connection
        if($connection === false){
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }
?>
