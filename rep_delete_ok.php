<?php
session_start();
// 에러 메세지 출력
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';
$post_id = $_GET['post_id'];
$no = $_GET['id'];

$query = "delete from reply where id='$no'";
$result = mysqli_query($conn, $query);
if($result){
    echo "<script> alert('삭제되었습니다');
    location.href='/read.php?id=$post_id';</script>";
}
?>