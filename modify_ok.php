<?php
include 'db_connect.php';
// 에러 메세지 출력
error_reporting(E_ALL);
ini_set('display_errors', 1);

// [변경 1] 'board' 식별자를 POST 방식으로 받아옵니다. (form의 method가 post이므로)
$board_id = isset($_POST['board']) ? $_POST['board'] : 'free';
$allowed_boards = ['free', 'guestbook'];
if (!in_array($board_id, $allowed_boards)) {
    die("존재하지 않는 게시판입니다.");
}

// [변경 2] 식별자를 이용해 테이블 이름을 동적으로 만듭니다.
$board_table = "board_" . $board_id;

$no = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];

if($title==NULL || $content==NULL){
    echo "<script>alert('빈 칸을 모두 채워주세요');
    history.back();</script>";
    // header('Location: ./login.html');
    exit;
}

if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
    // 새 파일이 업로드된 경우: 파일 처리 및 경로 업데이트
    $tmpfile =  $_FILES['file']['tmp_name'];
    $origin_name = basename($_FILES['file']['name']);
    $folder = "uploads/".$origin_name;
    move_uploaded_file($tmpfile, $folder);

    // [변경 3] 쿼리에서 테이블 이름을 변수로 변경하고, file_path도 업데이트합니다.
    $query = "UPDATE $board_table SET title='$title', content='$content', file_path='$origin_name' WHERE id=$no";

} else {
    // 새 파일이 업로드되지 않은 경우: 제목과 내용만 업데이트
    // [변경 3] 쿼리에서 테이블 이름을 변수로 변경하고, file_path는 건드리지 않습니다.
    $query = "UPDATE $board_table SET title='$title', content='$content' WHERE id=$no";
}


$result = mysqli_query($conn, $query);
// $board = mysqli_fetch_array($result);
if($result){
    echo "<script>alert('수정되었습니다');
    location.href='/main.php?board=$board_id'</script>";
}

?>