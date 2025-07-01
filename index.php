<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>게시판을 선택하세요</title>
    <style>
        body { font-family: 'Malgun Gothic', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f2f5; margin: 0; }
        .container { text-align: center; }
        h1 { margin-bottom: 40px; }
        .board-link {
            display: inline-block;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 20px 40px;
            margin: 0 20px;
            border-radius: 8px;
            font-size: 1.5em;
            font-weight: bold;
            transition: transform 0.2s, background-color 0.2s;
        }
        .board-link:hover {
            transform: scale(1.05);
            background-color: #0056b3;
        }
        .guestbook-link {
             background-color: #28a745;
        }
        .guestbook-link:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>게시판을 선택하세요</h1>
        <div>
            <a href="main.php?board=free" class="board-link">자유게시판</a>
            <a href="main.php?board=guestbook" class="board-link guestbook-link">방명록</a>
        </div>
    </div>
</body>
</html>