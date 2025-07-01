<?php

// 사용자 입력
// post가 body에 담아서 데이터를 전송하니까 post가 더 적절한듯(지금은 동작 확인 때문에 url에 확인가능한 get으로 사용)
$name = $_GET['user_name'];
$id = $_GET['user_id'];
$pw = $_GET['user_pwd'];
$cpw = $_GET['chk_pwd'];

if($id==NULL || $pw==NULL){
    echo "<script>alert('빈 칸을 모두 채워주세요');
    location.href='./sign_up.html'</script>";
    exit();
    // header('Location: ./sign_up.html');
}

// test@172.18.24.89
$servername = "127.0.0.1"; // mysql 호스트
$username = "test"; // mysql 사용자 이름
$password = "1234"; // mysql 비번
$dbname = "kknock_login"; // 데이터베이스 이름

// db connection
$conn = new mysqli($servername, $username, $password, $dbname);

// check connection
if(!$conn){
    die("Connection failed" . mysqli_connect_error());
}

// confirm passwd
if($pw!=$cpw){
    echo "<script>alert('비밀번호가 일치하지 않습니다');
    location.href='./sign_up.html'</script>";
    exit();
}


// 중복 처리
$query = "select * from users where user_id='$id'";
$result = mysqli_query($conn, $query);

if($result->num_rows > 0){
    echo "<script>alert('중복된 아이디 입니다');
    location.href='./sign_up.html'</script>";
    exit();
}

// 데이터 추가
$query = "insert into users (user_id, user_pwd, user_name) values ('$id', '$pw', '$name')";
$signup = mysqli_query($conn, $query);

if($signup){
    echo "<script>alert('회원가입 성공');
    location.href='./login.php'</script>";
}

mysqli_close($conn);

?>