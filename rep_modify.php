<?php
session_start();

include 'db_connect.php';

// [변경 1] 어떤 게시판인지 식별자를 받아옵니다.
$board_id = isset($_GET['board']) ? $_GET['board'] : 'free';
$allowed_boards = ['free', 'guestbook'];
if (!in_array($board_id, $allowed_boards)) {
    die("존재하지 않는 게시판입니다.");
}

// [변경 2] 식별자를 이용해 테이블 이름을 동적으로 만듭니다.
$reply_table = "reply_" . $board_id;


$no = $_GET['id'];
$post_id = $_GET['post_id'];

// [변경 3] 쿼리에서 테이블 이름을 변수로 변경합니다.
$query = "select * from $reply_table where id=$no";
$result = mysqli_query($conn, $query);
$reply = mysqli_fetch_array($result);
if($reply['user_id']!=$_SESSION['user_id']){
    echo "<script>alert('페이지 접근 권한이 없습니다');
    history.back();</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>수정</title>
    <style>
        body {
            font-family: 'Malgun Gothic', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .reply-modify-container {
            width: 100%;
            max-width: 600px;
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .reply-modify-container h1 {
            text-align: center;
            margin-top: 0;
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
        .form-group textarea {
            width: 100%;
            min-height: 150px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            resize: vertical;
        }
        .button-container {
            display: flex;
            justify-content: flex-end; /* 버튼을 오른쪽으로 정렬 */
            gap: 10px; /* 버튼 사이 간격 */
            margin-top: 20px;
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
    <div class="reply-modify-container">
        <h1>댓글 수정</h1>
        <form action="rep_modify_ok.php" method="get">
            <input type="hidden" name="id" value="<?php echo $no; ?>">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <input type="hidden" name="board" value="<?php echo $board_id; ?>">
            
            <div class="form-group">
                <label for="content">수정할 내용</label>
                <textarea name="content" id="content" placeholder="내용" required><?php echo $reply['content']; ?></textarea> 
            </div>
            
            <div class="button-container">
                <a href="javascript:history.back()" class="btn btn-cancel">취소</a>
                <button type="submit" class="btn btn-submit">수정 완료</button>
            </div>
        </form>
    </div>
</body>
</html>