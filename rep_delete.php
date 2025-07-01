<?php
session_start();
include 'db_connect.php';
// 에러 메세지 출력
error_reporting(E_ALL);
ini_set('display_errors', 1);
$no = $_GET['id'];
$post_id = $_GET['post_id'];

$query = "select * from reply where id=$no";
$result = mysqli_query($conn, $query);
$reply = mysqli_fetch_array($result);
if($_SESSION['user_id']!='admin' || $reply['user_id']!=$_SESSION['user_id']){
    echo "<script>alert('페이지 접근 권한이 없습니다');
    history.back();</script>";
}
echo "<script>
    if(confirm('정말로 삭제하겠습니까?')){
        location.href='/rep_delete_ok.php?id=$no&post_id=$post_id';
    } else{
        alert('취소되었습니다');
        history.back();
    }
</script>"
?>
