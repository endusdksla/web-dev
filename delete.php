<?php
session_start();
include 'db_connect.php';
// 에러 메세지 출력
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 어떤 게시판인지 식별자를 받아옵니다.
$board_id = isset($_GET['board']) ? $_GET['board'] : 'free';
$allowed_boards = ['free', 'guestbook'];
if (!in_array($board_id, $allowed_boards)) {
    die("존재하지 않는 게시판입니다.");
}

// 식별자를 이용해 테이블 이름을 동적으로 만듭니다.
$board_table = "board_" . $board_id;

$no = $_GET['id'];

// 쿼리에서 테이블 이름을 변수로 변경합니다.
$query = "select * from $board_table where id=$no";
$result = mysqli_query($conn, $query);
$board = mysqli_fetch_array($result);

if($_SESSION['user_id']!='admin' && $board['user_id']!=$_SESSION['user_id']){
    echo "<script>alert('페이지 접근 권한이 없습니다');
    history.back();</script>";
}
echo "<script>
    if(confirm('정말로 삭제하겠습니까?')){
        location.href='/delete_ok.php?id=$no&board=$board_id';
    } else{
        alert('취소되었습니다');
        history.back();
    }
</script>"
?>
