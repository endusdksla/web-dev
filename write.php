<?php
session_start();

// [변경 1] 어떤 게시판에 글을 쓸 것인지 식별자를 받아옵니다.
$board_id = isset($_GET['board']) ? $_GET['board'] : 'free';
$allowed_boards = ['free', 'guestbook'];
if (!in_array($board_id, $allowed_boards)) {
    die("존재하지 않는 게시판입니다.");
}

// [선택 사항] 게시판 이름에 따라 페이지 제목을 동적으로 변경합니다.
$page_title_prefix = ($board_id == 'free') ? "자유게시판" : "방명록";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>게시물 작성</title>
    <style>
        body {
            font-family: 'Malgun Gothic', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .write-container {
            width: 100%;
            max-width: 768px;
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .write-container h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }
        /* 작성자 이름 표시 스타일 */
        .author-display {
            width: 100%;
            padding: 12px;
            background-color: #e9ecef;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            color: #495057;
        }
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .form-group textarea#content {
            min-height: 250px;
            resize: vertical;
        }
        .form-group input[type="file"] {
            padding: 8px;
        }
        .button-container {
            display: flex;
            justify-content: flex-end; /* 버튼을 오른쪽으로 정렬 */
            gap: 10px; /* 버튼 사이 간격 */
            margin-top: 30px;
        }
        .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.2s;
        }
        .btn-submit {
            background-color: #007bff; /* 파란색 */
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .btn-cancel {
            background-color: #6c757d; /* 회색 */
        }
        .btn-cancel:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="write-container">
        <h1><?php echo $page_title_prefix; ?> 글 작성</h1>
        <form action="/write_ok.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="board" value="<?php echo $board_id; ?>">
            <div class="form-group">
                <label>작성자</label>
                <!-- 기존 p태그를 수정하여 디자인 적용 -->
                <div class="author-display"><?php echo $_SESSION['user_id']; ?></div>
            </div>

            <div class="form-group">
                <label for="title">제목</label>
                <textarea name="title" id="title" rows="1" placeholder="제목을 입력하세요" maxlength="100" required></textarea>
            </div>
            
            <!-- <textarea name="name" id="name" rows="1" cols="55" placeholder="글쓴이" maxlength="100" required></textarea> -->
            
            <div class="form-group">
                <label for="content">내용</label>
                <textarea name="content" id="content" placeholder="내용을 입력하세요" required></textarea>
            </div>

            <div class="form-group">
                <label for="file">첨부파일</label>
                <input type="file" name="file" id="file">
            </div>
            
            <div class="button-container">
                <a href="javascript:history.back()" class="btn btn-cancel">취소</a>
                <button type="submit" class="btn btn-submit">작성 완료</button>
            </div>
        </form>
    </div>
</body>
</html>