<?php
session_start();
// 에러 메세지 출력
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

// [변경 1] 어떤 게시판인지 식별자를 받아옵니다.
$board_id = isset($_GET['board']) ? $_GET['board'] : 'free';
$allowed_boards = ['free', 'guestbook'];
if (!in_array($board_id, $allowed_boards)) {
    die("존재하지 않는 게시판입니다.");
}

// [변경 2] 식별자를 이용해 테이블 이름을 동적으로 만듭니다.
$board_table = "board_" . $board_id;

$no = $_GET['id'];

$query = "delete from $board_table where id='$no'";
$result = mysqli_query($conn, $query);
echo "<script> alert('삭제되었습니다');
location.href='/main.php?board=$board_id';</script>"
?>