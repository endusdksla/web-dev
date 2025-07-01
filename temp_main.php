<?php
session_start();

// 에러 메세지 출력
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['user_id'])){   // 로그인 안되있으면
    header('Location: ./login.php'); // 로그인 페이지로 이동
}

echo "<h2>로그인 성공</h2><br><h2>";
echo $_SESSION['user_id'];
echo "님 안녕하세요</h2><br><br>";
echo "<a href=logout.php>로그아웃</a>";
$servername = "127.0.0.1"; // mysql 호스트
$username = "test"; // mysql 사용자 이름
$password = "1234"; // mysql 비번
$dbname = "kknock_login"; // 데이터베이스 이름

// db connection
$conn = new mysqli($servername, $username, $password, $dbname);

// 1. 사용자가 입력한 검색 카테고리와 키워드를 받아옵니다.
$search_category = isset($_GET['search_category']) ? $_GET['search_category'] : '';
$search_keyword = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : '';

// 2. 기본 SQL 쿼리를 준비합니다.
$query = "SELECT * FROM board";
$where_sql = ""; // WHERE 절을 저장할 변수

// 3. 검색어가 입력되었을 경우, WHERE 절을 동적으로 생성합니다.
if (!empty($search_category) && !empty($search_keyword)) {
    // SQL Injection 공격 방지를 위해 키워드를 안전하게 처리합니다.
    $escaped_keyword = mysqli_real_escape_string($conn, $search_keyword);

    switch ($search_category) {
        case 'title':
            $where_sql = " WHERE title LIKE '%$escaped_keyword%'";
            break;
        case 'content':
            $where_sql = " WHERE content LIKE '%$escaped_keyword%'";
            break;
        case 'user_id': // '글쓴이'를 user_id 컬럼으로 검색
            $where_sql = " WHERE user_id LIKE '%$escaped_keyword%'";
            break;
    }
}

// 4. 최종 쿼리를 조합합니다.
$query .= $where_sql; // WHERE 절 추가
$query .= " ORDER BY id DESC"; // 정렬 조건 추가

// 5. 검색을 하지 않았을 때만 상위 10개만 보이도록 LIMIT을 추가합니다.
if (empty($where_sql)) {
    $query .= " LIMIT 10";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
</head>
<body>
    <h1>게시판</h1><br>
    <div class="search-form">
        <form method="get" action="main.php">
            <select name="search_category">
                <!-- 검색 후에도 선택한 카테고리가 유지되도록 selected 속성 추가 -->
                <option value="title" <?php if($search_category == 'title') echo 'selected'; ?>>제목</option>
                <option value="content" <?php if($search_category == 'content') echo 'selected'; ?>>내용</option>
                <option value="user_id" <?php if($search_category == 'user_id') echo 'selected'; ?>>글쓴이</option>
            </select>
            <!-- 검색 후에도 입력한 키워드가 유지되도록 value 속성 추가 -->
            <input type="text" name="search_keyword" placeholder="검색어를 입력하세요" value="<?php echo htmlspecialchars($search_keyword); ?>" required>
            <button type="submit">검색</button>
        </form>
    </div>
    <table class="list-table">
        <thead>
            <tr>
                <th width=70>번호</th>
                <th width=500>제목</th>
                <th width=120>글쓴이</th>
                <th width=100>작성일</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $query = "select * from board order by id desc limit 10";
                $result = mysqli_query($conn, $query);
                if(mysqli_num_rows($result)>0){
                    while($board = mysqli_fetch_array($result)){
                        $title = $board["title"];
                        if(strlen($title)>30){
                            // 제목이 30글자 이상이면 뒤를 ...으로 표기
                            $title = str_replace($title, mb_substr($title, 0, 30, "utf-8")."...", $title);
                        }
                        ?>
                        <tr>
                            <td width="70"><?php echo $board['id']; ?></td>
                            <td width="500"><a href="/read.php?id=<?php echo $board['id'];?>"><?php echo $title;?></a></td>
                            <td width="120"><?php echo $board['user_id']?></td>
                            <td width="100"><?php echo $board['date']?></td>
                        </tr>
                        <?php
                    }
                } else{
                    ?>
                    <tr><td colspan="4" style="text-align:center;">게시물이 없습니다.</td></tr>
                    <?php
                }
            ?>
        </tbody>
    </table><br>
    <a href="/write.php"><button>게시물 작성</button></a>
    <!-- <form action="/write.php" method="GET">
    <label for="write">
        <button type="submit" id="write">게시물 작성</button>
    </label>
    </form> -->
</body>
</html>