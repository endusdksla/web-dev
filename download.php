<?php
$filename = "example.pdf"; // 다운로드할 파일명
$file = "path/to/files/" . $filename; // 파일 경로

// 파일 다운로드 헤더 설정
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . $filename);
header("Content-Length: " . filesize($file));

// 파일 출력
readfile($file);
?>