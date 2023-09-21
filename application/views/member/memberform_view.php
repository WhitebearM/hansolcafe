<!DOCTYPE html>
<meta charset="UTF-8">
<title>회원가입</title>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/css/member/memberform.css">
    <script src="/assets/js/member/memberform.js"></script>
</head>

<body>
    <div id="formlogo">
        <a href="/layout"><img src="/assets/images/icon-cafe.png" width="75px" height="75px"></a>
    </div>

    <form id="main_form" action="/member/memberform/create" method="post" enctype="multipart/form-data">
        <h2>회원가입</h2>
        <ul id="input_form">
            <li><input type="text" name="user_id" id="user_id" placeholder="아이디" required>
                <button type="button" id="idck">중복확인</button>
            </li>
            <li><span class="error" id="userid_error" style="color: red;"></span></li>

            <li><input class="input_move" type="password" name="user_pw" id="user_pw" placeholder="비밀번호" required>
                <a href="#" id="password_toggle"><img src="/assets/images/password.png" width="25" height="25"></a>
            </li>
            <li><span class="error" id="userpw_error" style="color: red;"></span></li>

            <li><input class="input_move" type="password" name="user_pw_verify" id="user_pw_verify"
                    placeholder="비밀번호 확인" required></li>
            <li><span class="error" id="userpw_verify_error" style="color: red;"></span></li>

            <li><input class="input_move" type="text" name="user_email" id="user_email" placeholder="이메일" required></li>
            <li><span class="error" id="useremail_error" style="color: red;"></span></li>

            <li><input class="input_move" type="text" name="user_name" id="user_name" placeholder="이름" required></li>
            <li><span class="error" id="username_error" style="color: red;"></span></li>
        </ul>
        <span id="profile_text">프로필사진 : </span>
        <input type="file" name="profilePic" id="profilePicInput" accept=".jpg, .jpeg, .png">
        <div id="previewContainer">
            <img id="previewImage" src="/assets/images/profile.png" alt="프로필 사진 미리보기" width="150px" height="150px">
        </div>
        <div id="success">
            <button id="formbtn" type="submit" disabled>회원가입</button>
        </div>
    </form>
</body>

</html>