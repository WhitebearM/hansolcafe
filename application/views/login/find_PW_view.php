<!DOCTYPE html>
<meta charset="UTF-8">

<head>
    <title>비밀번호 찾기</title>
    <script src="/assets/js/login/find_PW.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/login/find_PW.css">
</head>

<body>
    <a href="/layout">
        <div class="container" id="logo">
            <a href="/layout"><img src="/assets/images/icon-cafe.png" width="75px" height="75px"></a>
        </div>
    </a>
    <!-- 비밀번호 찾기전 -->
    <? if (!$result) { ?>
        <form class="container" id="find_pw_form" action="/login/find_PW/find_PW" method="post">
            <div id="pw_title">
                <h4>비밀번호 찾기</h4>
            </div>
            <input type="text" name="find_pw_id" placeholder="아이디" required><br>
            <input type="text" name="find_pw_email" placeholder="가입하실때 등록하신 이메일을 입력해주세요." required>
            <div>
                <button id="formbtn" type="submit" class="btn btn-secondary">비밀번호 찾기</button>
            </div>
            <div id="find_id_btn">
                <a href="/login/find_ID">아이디 찾기</a>
            </div>
        </form>
        <!-- 비밀번호 찾은 다음 변경 -->
    <? } else { ?>
        <form class="container" id="find_pw_form" action="/login/find_PW/update_PW" method="post">
            <div id="pw_title">
                <h4>비밀번호 변경</h4>
            </div>
            <input type="hidden" name="check_id" value="<?= $user_id ?>">
            <input type="password" name="change_pw" id="change_pw" placeholder="변경하실 비밀번호" required><br>
            <span class="error" id="change_pw_error" style="color: red;"></span><br>
            <input type="password" name="change_confirm" id="chage_confirm" placeholder="비밀번호 확인" required><br>
            <span class="error" id="change_confirm_error" style="color: red;"></span>
            <div>
                <button id="formbtn" type="submit" class="btn btn-secondary" disabled>비밀번호 변경</button>
            </div>
            <div id="find_id_btn">
                <a href="/login/find_ID">아이디 찾기</a>
            </div>
        </form>
    <? } ?>
</body>

</html>