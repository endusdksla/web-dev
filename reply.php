<?php
include 'db_connect.php';
// 에러 메세지 출력
error_reporting(E_ALL);
ini_set('display_errors', 1);

// [변경 1] 어떤 게시판인지 식별자를 받아옵니다.
$board_id = isset($_GET['board']) ? $_GET['board'] : 'free';
$allowed_boards = ['free', 'guestbook'];
if (!in_array($board_id, $allowed_boards)) {
    die("존재하지 않는 게시판입니다.");
}

// [변경 2] 식별자를 이용해 댓글 테이블 이름을 동적으로 만듭니다.
$reply_table = "reply_" . $board_id;

$post_id = $_GET['post_id'];
$user_id = $_GET['user_id'];
$content = $_GET['content'];
$date = date("Y-m-d H:i:s");

if(empty(trim($content))){
    echo "<script>alert('빈 칸을 모두 채워주세요');
    history.back();</script>";
    exit;
}

$query = "insert into $reply_table(post_id, user_id, content, date) values('$post_id', '$user_id', '$content', '$date')";
$result = mysqli_query($conn, $query);
if($result){
    header("Location: ./read.php?id=$post_id&board=$board_id");
} else {
    // 쿼리 실패 시 사용자에게 알림
    echo "<script>alert('댓글 등록에 실패했습니다. 다시 시도해주세요.'); history.back();</script>";
}

?>