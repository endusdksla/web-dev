<?php
session_start();
include 'db_connect.php';
// 에러 메세지 출력
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 사용자 입력
$id = $_GET['user_id'];
$pw = $_GET['user_pwd'];
$remind = $_GET['remind'];

if($id==NULL || $pw==NULL){
    echo "<script>alert('빈 칸을 모두 채워주세요');
    location.href='./login.php'</script>";
    // header('Location: ./login.html');
}


// query to db server 
$query = "select * from users where user_id='$id'";
$result = mysqli_query($conn, $query);

if($result){
    $row = mysqli_fetch_array($result); // id와 동일한 행을 가져옴
    if($row['user_pwd'] == $pw){
        $_SESSION['user_id'] = $id;
        if(isset($_SESSION['user_id'])){
            if(isset($remind)){
                setcookie("user_id", $id, time() + 3600, "/");
                setcookie("user_pwd", $pw, time() + 3600, "/");
            } else {
                // 체크되지 않은 경우: 기존 쿠키 삭제
                // 쿠키의 유효기간을 과거로 설정하여 즉시 만료시킵니다.
                setcookie("user_id", "", time() - 3600, "/");
                setcookie("user_pwd", "", time() - 3600, "/");
            }
            header('Location: ./main.php');
        } else{
            echo "세션 저장 실패";
        }
    } else{
        echo "<script>alert('잘못된 아이디 또는 비밀번호 입니다.');
        location.href='./login.php'</script>";
        // header('Location: ./login.html');
    }
} else{
    echo "<script>alert('잘못된 아이디 또는 비밀번호 입니다.');
    location.href='./login.php'</script>";
    // header('Location: ./login.html');
}

mysqli_close($conn);

?>