<?php
session_start();
include 'db_connect.php';

if(!isset($_SESSION['user_id'])){   // 로그인 안되있으면
    header('Location: ./login.php'); // 로그인 페이지로 이동
}

// [변경 1] 어떤 게시판인지 식별자를 받아옵니다.
$board_id = isset($_GET['board']) ? $_GET['board'] : 'free'; // 기본값 'free'
$allowed_boards = ['free', 'guestbook'];
if (!in_array($board_id, $allowed_boards)) {
    die("존재하지 않는 게시판입니다.");
}

// [변경 2] 식별자를 이용해 테이블 이름을 동적으로 만듭니다.
$board_table = "board_" . $board_id;
$reply_table = "reply_" . $board_id;

$no = $_GET['id'];

// [변경 3] 쿼리에서 하드코딩된 테이블 이름 'board'를 변수로 변경합니다.
$query = "select * from $board_table where id=$no";
$result = mysqli_query($conn, $query);
$board = mysqli_fetch_array($result);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $board['title']?></title>
    <style>
        body {
            font-family: Arial, "Malgun Gothic", sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        h2 {
            font-size: 2.2em;
            color: #212529;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-top: 0;
        }
        
        body > br { display: block; margin-bottom: 20px; }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin-top: 30px;
            margin-bottom: 20px;
            text-align: right;
        }
        ul li {
            display: inline-block;
            margin-left: 8px;
        }
        ul li a {
            text-decoration: none;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: bold;
        }
        ul li:nth-child(1) a { background-color: #6c757d; }
        ul li:nth-child(2) a { background-color: #007bff; }
        ul li:nth-child(3) a { background-color: #dc3545; }
        
        hr {
            border: 0;
            height: 1px;
            background-color: #e9ecef;
            margin: 30px 0;
        }

        h3 {
            font-size: 1.5em;
            color: #495057;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }
        
        form[action="reply.php"] {
            margin-top: 20px;
            overflow: hidden; /* float 해제 */
        }
        form[action="reply.php"] textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ced4da;
            border-radius: 4px;
            resize: vertical;
            margin-bottom: 10px;
        }
        form[action="reply.php"] button {
            float: right;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .reply-container div {
            padding: 15px 0;
            border-top: 1px solid #f1f3f5;
        }
        .reply-container b {
            font-size: 1.1em;
            color: #000;
        }
        .reply-container p {
            margin-top: 5px;
            padding-left: 5px;
        }

        /* --- [댓글 버튼 스타일 - 클래스 기반] --- */
        .reply-btn {
            display: inline-block;
            text-decoration: none;
            font-size: 0.8em;
            color: #fff !important; /* !important로 다른 스타일에 덮어쓰이지 않게 함 */
            padding: 4px 8px;
            border-radius: 4px;
            margin-left: 5px;
            transition: opacity 0.2s;
        }
        .btn-modify {
            background-color: #6c757d; /* 회색 */
        }
        .btn-delete {
            background-color: #dc3545; /* 빨간색 */
        }
        .reply-btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <h2><?php echo $board['title']; ?></h2>
    <?php echo $board['name']; ?>
    <?php echo $board['date']; ?><br>
    <!-- <a href="uploads/<?php echo $board['file_path'];?>" download><?php echo $board['file_path']; ?></a> -->
    <?php if($board['file_path']!=NULL){ ?>
        <a href="uploads/<?php echo $board['file_path'];?>" download><img src="uploads/<?php echo $board['file_path']; ?>" alt="파일" width="450" height="250"></a><br>
    <?php }?>
    <?php echo nl2br("$board[content]"); ?>
    <ul>
        <li><a href="/main.php?board=<?php echo $board_id; ?>">[목록으로]</a></li>
        <?php
        if(isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 'admin' || $board['user_id'] == $_SESSION['user_id'])){ ?>
            <li><a href="modify.php?id=<?php echo $board['id']; ?>&board=<?php echo $board_id; ?>">[수정]</a></li>
            <li><a href="delete.php?id=<?php echo $board['id']; ?>&board=<?php echo $board_id; ?>">[삭제]</a></li>
        <?php } ?>
    </ul>
    <hr>
    <h3>덧글</h3>
    <form action="reply.php" method="get">
        <input type=hidden name="post_id" value="<?php echo $no; ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
        <input type="hidden" name="board" value="<?php echo $board_id; ?>">
        <textarea name="content"></textarea>
        <button type="submit">댓글</button>
    </form>

    <?php
    $query = "select * from $reply_table where post_id='$no' order by id desc";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("쿼리 실행 실패: " . mysqli_error($conn)); // 오류 확인
    }
    if(mysqli_num_rows($result)>0){
        while($reply = mysqli_fetch_array($result)){
            ?>
            <div>
                <br>
                <b>no. </b> <?php echo $reply['id']; ?>
                <b>이름: </b> <?php echo $reply['user_id'];?>
                <b>날짜: </b> <?php echo $reply['date'];?>
                <?php
                if($_SESSION['user_id']=='admin'||$reply['user_id']==$_SESSION['user_id']){ ?>
                    <a class="reply-btn btn-modify" href="rep_modify.php?id=<?php echo $reply['id']; ?>&post_id=<?php echo $reply['post_id']; ?>&board=<?php echo $board_id; ?>">[수정]</a>
                    <a class="reply-btn btn-delete" href="rep_delete.php?id=<?php echo $reply['id']; ?>&post_id=<?php echo $reply['post_id']; ?>&board=<?php echo $board_id; ?>">[삭제]</a>
                <?php } ?>
                <br><p><?php echo $reply['content']; ?></p>
            </div>
            <?php
        }
    }
    ?>
    <!-- <form action=""></form> -->
</body>
</html>