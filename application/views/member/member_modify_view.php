<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>회원정보수정</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/css/member/member_modify.css">
    <script src="/assets/js/member/member_modify.js"></script>

</head>
<body>
    <a href="/layout">
        <div id="logo" class="container">
            <a href="/layout"><img src="/assets/images/icon-cafe.png" width="75px" height="75px"></a>
        </div>
    </a>
    <form class="container" id="modify_form" action="/member/member_modify/member_update" method="post"
        enctype="multipart/form-data">
        <div id="modify_title">
            <h5>회원정보 수정</h5>
        </div>
        <div>
            <? if ($info["image_path"] != "") { ?>
                <div id="previewContainer">
                    <img id="previewImage" src="<?= $info["image_path"] ?>" alt="프로필 사진 미리보기" width="150px" height="150px">
                </div>
            <? } else { ?>
                <div id="previewContainer">
                    <img id="previewImage" src="/assets/images/profile.png" alt="프로필 사진 미리보기" width="150px" height="150px">
                </div>
            <? } ?>
            <div id="preview_textContainer">
                <span id="profile_text">프로필사진 : </span>
                <input type="file" name="profilePic" id="profilePicInput" accept=".jpg, .jpeg, .png">
            </div>
        </div>
        <div id="modify_input">
            <ul>
                <li><input type="text" name="user_id" value="<?= $info['user_id'] ?>" readonly></li>
                <li><input type="text" name="user_nickname" value="<?= $info['user_nickname'] ?>" required></li>
                <li><input type="password" name="user_pw" name="check_pw" id="check_pw" placeholder="비밀번호">
                </li>
                <span class="error" id="change_pw_error" style="color: red;"></span>
                <li><input type="password" name="user_pw" name="check_confirm" id="check_confirm" placeholder="비밀번호 확인"></li>
                <span class="error" id="change_confirm_error" style="color: red;"></span>
                <li><input type="email" name="user_email" id="user_email" value="<?= $info['user_email'] ?>"
                        required></li>
                <span class="error" id="change_email_error" style="color: red;"></span>
            </ul>
        </div>
        <div id="modify_btn">
            <button id="modifybtn" type="submit" class="btn btn-secondary" disabled>수정</button>
            <a href="/layout"><button type="button" class="btn btn-secondary">취소</button></a>
        </div>
    </form>
</body>

</html>