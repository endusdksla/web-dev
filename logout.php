<?php
session_start();

$res = session_destroy(); // 세션 삭제

if($res){
    header('Location: ./main.php');
}
?>