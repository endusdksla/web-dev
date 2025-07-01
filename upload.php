<?php
// 그냥 files db를 하나 더 만들어서 게시물처럼 auto_increment로 고유한 id를 각 각 부여하고, 게시물 id도 참조하면 될듯
// 일단 블로그는 board에 컬럼을 추가함
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $file = $_FILES["file"];

    // 파일 유효성 검사
    if ($file["error"] === UPLOAD_ERR_OK) {
        $filename = $file["name"];
        $filetmp = $file["tmp_name"];
        $destination = "uploads/" . $filename;

        // 파일 이동
        if (move_uploaded_file($filetmp, $destination)) {
            echo "파일 업로드 성공!";
        } else {
            echo "파일 업로드 실패.";
        }
    } else {
        echo "파일 업로드 오류: " . $file["error"];
    }
}
?>