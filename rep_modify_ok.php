<?php
include 'db_connect.php';
// 에러 메세지 출력
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 어떤 게시판인지 식별자를 GET 방식으로 받아옵니다.
$board_id = isset($_GET['board']) ? $_GET['board'] : 'free';
$allowed_boards = ['free', 'guestbook'];
if (!in_array($board_id, $allowed_boards)) {
    die("존재하지 않는 게시판입니다.");
}

// 식별자를 이용해 댓글 테이블 이름을 동적으로 만듭니다.
$reply_table = "reply_" . $board_id;

$no = $_GET['id'];
$post_id = $_GET['post_id'];
$content = $_GET['content'];

// [로직 개선] 빈 칸 및 공백만 있는 입력을 막기 위한 체크 추가
if(empty(trim($content))){
    echo "<script>alert('내용을 입력해주세요.');
    history.back();</script>";
    exit;
}

$query = "update $reply_table set content='$content' where id=$no";
$result = mysqli_query($conn, $query);
if($result){
    echo "<script>alert('수정되었습니다');
    location.href='/read.php?id=$post_id&board=$board_id';</script>";
}

?>
