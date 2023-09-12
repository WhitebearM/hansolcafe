<!doctype html>

<head>
    <title></title>
    <link rel="stylesheet" href="/assets/css/member/member_delete.css">
    <script src="/assets/js/member/member_delete.js"></script>
</head>

<body>
    <div class="delete_logo">
        <a href="/layout"><img src="/assets/images/logolo.png" rel="img" width="70" height="70"></a>
    </div>
    <div class="delete_box_box">
        <form action="/member/member_delete/member_del" method="post" id="delete_mem">
            <h5>회원 탈퇴</h5>
            <ul class="delete_input">
                <li><input type="text" name="delete_id" placeholder="아이디"></li>
                <li><input type="password" name="delete_pw" placeholder="비밀번호"></li>
                <li><input type="text" name="delete_email" placeholder="이메일"></li>
            </ul>
            <button type="button" class="btn hover3" id="hi">회원탈퇴</button>
        </form>
    </div>
</body>

</html>