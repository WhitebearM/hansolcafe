<!DOCTYPE html>
<header>
    <meta charset="UTF-8">
    <title>로그인</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/login/login.css">
</header>

<body>
    <a href="/layout">
        <div class="container"id="logo">
            <a href="/layout"><img src="/assets/images/icon-cafe.png" width="75px" height="75px"></a>
        </div>
    </a>
    <div class="container" id="logintitle">
        <h4>로그인</h4>
    </div>
    <form class="container" id="loginform" action="/login/login/login_member" method="post">
        <input class="logininput" type="text" name="user_id" placeholder="아이디" required><br>
        <input class="logininput" type="password" name="user_pw" placeholder="비밀번호" required>
        <div class="container">
            <button id="formbtn" type="submit" class="btn btn-secondary">로그인</button>
        </div>
        <div>
            <ul id="login_list">
                <li><a href="/login/find_ID">아이디 찾기</a></li>&emsp;
                <li><a href="/login/find_PW">비밀번호 찾기</a></li>&emsp;
                <li><a href="/member/memberform">회원 가입</a></li>
            </ul>
        </div>
    </form>
</body>

</html>