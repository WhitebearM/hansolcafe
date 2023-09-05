<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>아이디 찾기</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/assets/css/login/find_ID.css">
</head>

<body>
    <!-- 아이디를 찾기전 -->
    <? if (!$result) { ?>
        <a href="/layout">
            <div class="container" id="logo">
                <a href="/layout"><img src="/assets/images/icon-cafe.png" width="75px" height="75px"></a>
            </div>
        </a>
        <form class="container" id="find_id_form" action="/login/find_ID/find_ID" method="post">
            <div class="container" id="find_id_title">
                <h4>아이디 찾기</h4>
            </div>
            <input type="text" name="find_id_email" placeholder="가입하실때 등록한 이메일을 입력해주세요." required>
            <div>
                <button id="formbtn" type="submit" class="btn btn-secondary">아이디 찾기</button>
            </div>
            <div id="move_find_pw">
                <a href="/login/find_pw">비밀번호 찾기</a>
            </div>
        </form>
        <!-- 아이디를 찾은후 -->
    <? } else { ?>
        <a href="/layout">
            <div class="container" id="logo">
                <a href="/layout"><img src="/assets/images/icon-cafe.png" width="75px" height="75px"></a>
            </div>
        </a>
        <div class="container" id="find_id_form">
            <div class="container" id="find_id_title">
                <h4>아이디 결과</h4>
            </div>
            <div id="find_result">
                <?= "조회하신 아이디는 " . $result->user_id . " 입니다." ?>
            </div>
            <div>
                <a href="/login/login"><button id="formbtn" type="button" class="btn btn-secondary">로그인</button></a>
            </div>
            <div id="move_find_pw">
                <a href="/login/find_pw">비밀번호 찾기</a>
            </div>
        </div>
    <? } ?>
</body>

</html>