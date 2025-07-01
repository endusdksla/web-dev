<?php
$servername = "127.0.0.1"; // mysql 호스트
$username = "test"; // mysql 사용자 이름
$password = "1234"; // mysql 비번
$dbname = "kknock_login"; // 데이터베이스 이름

// db connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if(!$conn){
    die("Connection failed" . mysqli_connect_error());
}
?>