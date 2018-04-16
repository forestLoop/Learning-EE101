<?php
    $servername="localhost";
    $username="root";
    $password="";
    $database="academicdb";
    $conn=new mysqli($servername,$username,$password,$database);
    if($conn->connect_error){
        die("Failed to connect to the database:".$conn->connect_error);
    }
 ?>
