<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: Arial, "Malgun Gothic", sans-serif;
      background-color: #f0f2f5; /* 전체 페이지 배경색 */
    }
    body {
      display: flex;
      justify-content: center; /* 가로 중앙 정렬 */
      align-items: center;    /* 세로 중앙 정렬 */
    }
    .login-wrapper {
      width: 100%;
      max-width: 400px;
      padding: 40px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      box-sizing: border-box;
    }
    .login-wrapper h2 {
      text-align: center;
      font-size: 24px;
      font-weight: bold;
      color: #333;
      margin-top: 0;
      margin-bottom: 30px;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-size: 14px;
      color: #555;
    }
    .form-group input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
      font-size: 16px;
    }
    .form-group input:focus {
      outline: none;
      border-color: #007bff; /* 포커스 시 테두리 색상 변경 */
    }
    .remember-group {
      display: flex;
      align-items: center;
      margin-bottom: 25px;
      font-size: 14px;
    }
    .remember-group input[type="checkbox"] {
      margin-right: 8px;
    }
    button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    .login-btn {
      background-color: #007bff; /* 로그인 버튼 파란색 */
      margin-bottom: 15px;
    }
    .login-btn:hover {
      background-color: #0056b3;
    }
    .signup-btn {
      background-color: #28a745; /* 회원가입 버튼 녹색 */
    }
    .signup-btn:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <form method="GET" action="/login_chk.php">
      <h2>Login</h2>
      <!-- ID 입력 필드 -->
      <div class="form-group">
          <label for="user_id">ID</label>
          <input type="text" id="user_id" name="user_id" placeholder="아이디를 입력하세요" value="<?php echo isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : ''; ?>"/>
      </div>
      <!-- PW 입력 필드 -->
      <div class="form-group">
          <label for="user_pwd">Password</label>
          <input type="password" id="user_pwd" name="user_pwd" placeholder="비밀번호를 입력하세요" value="<?php echo isset($_COOKIE['user_pwd']) ? $_COOKIE['user_pwd'] : ''; ?>" autocomplete="current-password"/>
      </div>
      <!-- 아이디 저장 체크박스 -->
      <div class="remember-group">
        <input type="checkbox" id="remember-check" name="remind" <?php if(isset($_COOKIE['user_id'])){ echo "checked"; } ?>>
        <label for="remember-check">Remember me</label>
      </div>
      <!-- 로그인 버튼 -->
      <button type="submit" class="login-btn">Login</button>
    </form>
    <!-- 회원가입 버튼 -->
    <button onclick="location.href='sign_up.html'" class="signup-btn">Sign Up</button>
  </div>
</body>
</html>