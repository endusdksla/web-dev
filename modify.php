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

// 권한 체크 (관리자이거나, 본인 글일 경우에만 접근 가능하도록 수정)
if ($_SESSION['user_id'] != 'admin' && $board['user_id'] != $_SESSION['user_id']) {
    echo "<script>alert('페이지 접근 권한이 없습니다');
    history.back();</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시물 수정</title>
    <!-- 스타일은 기존과 동일하게 유지합니다. -->
    <style>
        body { font-family: 'Malgun Gothic', sans-serif; background-color: #f0f2f5; margin: 0; padding: 40px; display: flex; justify-content: center; align-items: center; }
        .modify-container { width: 100%; max-width: 768px; background: #fff; padding: 30px 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
        .modify-container h1 { text-align: center; margin-bottom: 30px; font-size: 24px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 8px; }
        .form-group textarea, .form-group input[type="file"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 16px; }
        .form-group textarea#content { min-height: 250px; resize: vertical; }
        .form-group input[type="file"] { padding: 8px; }
        #imagePreview { display: block; max-width: 100%; height: auto; margin: 10px auto; border-radius: 5px; }
        .button-container { display: flex; justify-content: flex-end; gap: 10px; margin-top: 30px; }
        .btn { padding: 10px 25px; border: none; border-radius: 5px; color: white; font-size: 16px; font-weight: bold; cursor: pointer; text-decoration: none; text-align: center; }
        .btn-submit { background-color: #007bff; }
        .btn-cancel { background-color: #6c757d; }
    </style>
</head>
<body>
    <div class="modify-container">
        <h1>게시물 수정</h1>
        <form action="modify_ok.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $no; ?>">
            <input type="hidden" name="board" value="<?php echo $board_id; ?>">
            
            <div class="form-group">
                <label for="title">제목</label>
                <textarea name="title" id="title" rows="1" placeholder="제목" maxlength="100" required><?php echo htmlspecialchars($board['title']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="content">내용</label>
                <textarea name="content" id="content" placeholder="내용" required><?php echo htmlspecialchars($board['content']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="fileInput">첨부파일 (변경할 경우에만 선택)</label>

                <!-- PHP if문을 사용하여 파일 경로가 있을 때만 이미지 태그를 보여줍니다. -->
                <?php if (!empty($board['file_path'])): ?>
                    <a href="uploads/<?php echo htmlspecialchars($board['file_path']);?>" download>
                        <img id="imagePreview" src="uploads/<?php echo htmlspecialchars($board['file_path']); ?>" alt="파일 미리보기" width="450" height="250" style="border:1px solid #ccc; object-fit: cover;">
                    </a><br>
                <?php else: ?>
                    <!-- 파일이 없을 때는, JavaScript가 미리보기를 표시할 수 있도록 비어있는 img 태그를 숨겨서 준비해둡니다. -->
                    <img id="imagePreview" src="" alt="파일 미리보기" width="450" height="250" style="border:1px solid #ccc; object-fit: cover; display: none;">
                <?php endif; ?>

                <input type="file" name="file" id="fileInput">
            </div>

            <div class="button-container">
                <a href="javascript:history.back()" class="btn btn-cancel">취소</a>
                <button type="submit" class="btn btn-submit">글 수정</button>
            </div>
        </form>
    </div>
    
    <script>
        const fileInput = document.getElementById('fileInput');
        const imagePreview = document.getElementById('imagePreview');

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // 새 파일을 선택하면, 이미지 소스를 변경하고
                    imagePreview.src = e.target.result;
                    // 숨겨져 있던 img 태그를 보이게 만듭니다.
                    imagePreview.style.display = 'block'; 
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
