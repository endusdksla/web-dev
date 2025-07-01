<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header('Location: ./login.php');
    exit;
}

$board_id = isset($_GET['board']) ? $_GET['board'] : 'free';
$search_category = isset($_GET['search_category']) ? $_GET['search_category'] : 'free';
$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'desc';

// 게시판 유효성 검사
$allowed_boards = ['free', 'guestbook'];
if (!in_array($board_id, $allowed_boards)) {
    die("존재하지 않는 게시판입니다.");
}

// 페이지 제목 설정
$page_title = ($board_id == 'free') ? "자유게시판" : "방명록";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <style>
        body { font-family: 'Malgun Gothic', sans-serif; }
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; }
        .list-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .list-table th, .list-table td { border-bottom: 1px solid #ddd; padding: 12px; text-align: center; }
        .list-table th { background-color: #f8f9fa; color: #333; border-top: 2px solid #6c757d; }
        .list-table td { color: #555; }
        .list-table td a { text-decoration: none; color: #212529; font-weight: 500; transition: color 0.2s ease-in-out; }
        .list-table td a:hover { color: #007bff; }
        .search-form { text-align: center; padding: 20px; background-color: #f9f9f9; border-radius: 5px; }
        .search-form select, .search-form input[type="text"], .search-form button { padding: 8px; border: 1px solid #ccc; border-radius: 3px; margin-left: 5px; vertical-align: middle; }
        .search-form button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        .welcome-msg { text-align: right; padding: 10px 0; margin-bottom: 20px; }
        .welcome-msg span { font-size: 1.1em; font-weight: bold; vertical-align: middle; }
        .logout-btn { display: inline-block; text-decoration: none; color: white; background-color: #6c757d; padding: 8px 15px; border-radius: 5px; margin-left: 15px; vertical-align: middle; transition: background-color 0.2s; }
        .logout-btn:hover { background-color: #5a6268; }
        .btn-container { text-align: right; margin-bottom: 20px; }
        .write-btn { display: inline-block; text-decoration: none; color: white; background-color: #007bff; padding: 10px 20px; border-radius: 5px; font-weight: bold; transition: background-color 0.2s; }
        .write-btn:hover { background-color: #0056b3; }
        .board-nav { margin-bottom: 20px; border-bottom: 2px solid #dee2e6; }
        .board-nav ul { list-style: none; padding: 0; margin: 0; display: flex; }
        .board-nav li a { display: block; padding: 10px 20px; text-decoration: none; color: #495057; font-weight: bold; border: 2px solid transparent; border-bottom: none; margin-bottom: -2px; }
        .board-nav li.active a { color: #007bff; border-color: #dee2e6; border-radius: 5px 5px 0 0; background-color: white; }
    </style>
</head>
<body>
<div class="container">
    <div class="welcome-msg">
        <!-- 환영 메시지를 먼저 출력 -->
        <span><?php echo htmlspecialchars($_SESSION['user_id']); ?>님 안녕하세요</span>
        <a href="logout.php" class="logout-btn">로그아웃</a>
    </div>

    <h1><?php echo $page_title; ?></h1>
    <nav class="board-nav">
        <ul>
            <li class="<?php if($board_id == 'free') echo 'active'; ?>">
                <a href="main.php?board=free">자유게시판</a>
            </li>
            <li class="<?php if($board_id == 'guestbook') echo 'active'; ?>">
                <a href="main.php?board=guestbook">방명록</a>
            </li>
        </ul>
    </nav>
    <table class="list-table">
        <thead>
            <tr>
                <th width="70">번호</th>
                <th width="500">제목</th>
                <th width="120">글쓴이</th>
                <th width="100">작성일</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $servername = "127.0.0.1";
                $username = "test";
                $password = "1234";
                $dbname = "kknock_login";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $board_table = "board_" . $board_id;
                $query = "SELECT * FROM {$board_table}";
                $where_sql = "";

                if (!empty($search_category) && !empty($search_keyword)) {
                    $escaped_keyword = mysqli_real_escape_string($conn, $search_keyword);
                    switch ($search_category) {
                        case 'title':
                            $where_sql = " WHERE title LIKE '%$escaped_keyword%'";
                            break;
                        case 'content':
                            $where_sql = " WHERE content LIKE '%$escaped_keyword%'";
                            break;
                        case 'user_id':
                            $where_sql = " WHERE user_id LIKE '%$escaped_keyword%'";
                            break;
                    }
                }

                $query .= $where_sql;

                if ($sort_order == 'asc') {
                    $query .= " ORDER BY id ASC";
                } else {
                    $query .= " ORDER BY id DESC";
                }

                if (empty($where_sql)) {
                    $query .= " LIMIT 10";
                }
                
                $result = mysqli_query($conn, $query);
                if (!$result) {
                    die("쿼리 실행 실패: " . mysqli_error($conn));
                }

                if(mysqli_num_rows($result) > 0){
                    while($board = mysqli_fetch_array($result)){
                        $title = htmlspecialchars($board["title"]);
                        if(mb_strlen($title, "utf-8") > 30){
                            $title = mb_substr($title, 0, 30, "utf-8")."...";
                        }
                        ?>
                        <tr>
                            <td><?php echo $board['id']; ?></td>
                            <td style="text-align: left; padding-left: 20px;"><a href="/read.php?id=<?php echo $board['id'];?>&board=<?php echo $board_id; ?>"><?php echo $title;?></a></td>
                            <td><?php echo htmlspecialchars($board['user_id']); ?></td>
                            <td><?php echo $board['date']; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr><td colspan="4">게시물이 없습니다.</td></tr>
                    <?php
                }
            ?>
        </tbody>
    </table>
    
    <div class="btn-container">
        <a href="/write.php?board=<?php echo $board_id; ?>" class="write-btn">게시물 작성</a>
    </div>
    <hr>

    <div class="search-form">
        <form method="get" action="main.php">
            <input type="hidden" name="board" value="<?php echo $board_id; ?>">
            <select name="search_category">
                <option value="title" <?php if($search_category == 'title') echo 'selected'; ?>>제목</option>
                <option value="content" <?php if($search_category == 'content') echo 'selected'; ?>>내용</option>
                <option value="user_id" <?php if($search_category == 'user_id') echo 'selected'; ?>>글쓴이</option>
            </select>
            <input type="text" name="search_keyword" placeholder="검색어를 입력하세요" value="<?php echo htmlspecialchars($search_keyword); ?>">
            <select name="sort_order">
                <option value="desc" <?php if($sort_order == 'desc') echo 'selected'; ?>>최신순</option>
                <option value="asc" <?php if($sort_order == 'asc') echo 'selected'; ?>>오래된순</option>
            </select>
            <button type="submit">검색</button>
        </form>
    </div>
</div>
</body>
</html>
