<?php
session_start();
include 'db_connect.php';
// 에러 메세지 출력
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 'board' 식별자를 POST 방식으로 받아옵니다.
$board_id = isset($_POST['board']) ? $_POST['board'] : 'free';
$allowed_boards = ['free', 'guestbook'];
if (!in_array($board_id, $allowed_boards)) {
    die("존재하지 않는 게시판입니다.");
}

// 식별자를 이용해 테이블 이름을 동적으로 만듭니다.
$board_table = "board_" . $board_id;

// 사용자 입력
// $name = $_POST['name'];
$title = $_POST['title'];
$content = $_POST['content'];
$date = date("Y-m-d H:i:s");
$user = $_SESSION['user_id'];

if($title==NULL || $content==NULL){
    echo "<script>alert('빈 칸을 모두 채워주세요');
    history.back()</script>";
    // header('Location: ./login.html');
    exit;
}

$tmpfile =  $_FILES['file']['tmp_name']; // 임시 폴더에 파일 저장
$origin_name = basename($_FILES['file']['name']); // 경로 제거하고 파일명(+확장자명)만 추출
// $filename = iconv("UTF-8", "EUC-KR",$_FILES['file']['name']);    // 예전에 사용되었던 한글 인코딩 바꾸기
$folder = "uploads/".$origin_name; // 저장 경로
move_uploaded_file($tmpfile, $folder);



// query to db server 
// $query = "insert into board(title, content, user_id, date) values('"$title"', '"$content"', '"$_SESSION['user_id']"', '"$date'")";
if($user && $date && $title && $content){
    $query = "INSERT INTO $board_table(title, content, user_id, date, file_path) VALUES('$title', '$content', '$user', '$date', '$origin_name')";
    $result = mysqli_query($conn, $query);
    echo "<script>
    alert('글쓰기 완료되었습니다.');
    location.href='main.php?board=$board_id';</script>";
}else{
    // echo "<script>
    // alert('글쓰기에 실패했습니다.');
    // history.back();</script>";
    echo "<script>
    alert('글쓰기에 실패했습니다.');</script>";
}

?>
