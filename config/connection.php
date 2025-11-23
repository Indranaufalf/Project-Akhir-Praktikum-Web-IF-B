<?php

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "projekpw";

    $db = new mysqli($host, $user, $password, $database);

    if($db->connect_errno){
        die("Error coy!" . $db->connect_error);
    }
?>