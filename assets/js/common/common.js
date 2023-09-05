document.addEventListener("DOMContentLoaded", function () {
    const submitButton = document.getElementById("submitCategory");
    const categoryForm = document.getElementById("categoryForm");

    const act_page1 = document.getElementById("activity_page_move1");

    submitButton.addEventListener("click", function () {
        categoryForm.submit();
    });

    // 카테고리 추가 유효성검사
    const categoryNameInput = document.getElementById("category_name");
    const category_error = document.getElementById("category_error");
    const category_button = document.getElementById("submitCategory");

    categoryNameInput.addEventListener("input", function () {
        const categoryName = categoryNameInput.value.trim();
        if (categoryName === "") {
            category_error.textContent = "이름을 입력해주세요";
            category_error.style.color = "red";
            category_button.disabled = true;
        } else {
            $.ajax({
                url: '/layout/check_category',
                method: 'POST',
                data: {
                    category_name: categoryName
                },
                dataType: 'json',
                success: function (response) {
                    if (response.duplicate) {
                        category_error.textContent = "이미존재하는 카테고리 이름입니다.";
                        category_error.style.color = "red";
                        category_button.disabled = true;

                    } else {
                        category_error.textContent = "생성 가능합니다.";
                        category_error.style.color = "green";
                        category_button.disabled = false;
                    }
                },
                error: function () {
                    category_error.textContent = "오류가 발생했습니다.";
                }

            });
        }
    });

});

// 카페정보부분
function info_move($num) {
    var cafe_info = document.getElementById('cafe_info');
    var member_info = document.getElementById('member_info');

    if ($num === 'cafe') {
        cafe_info.style.display = 'block';
        member_info.style.display = 'none';
    } else if ($num === 'member') {
        $.ajax({
            url: '/layout/ck_login',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.logged_in) {
                    $('#cafe_info').hide();
                    $('#member_info').show();
                } else {
                    alert("로그인이 필요합니다.");
                    window.location.href = "/login/login";
                }
            },
            error: function () {
                alert("알수없는 오류가 발생했습니다.");
            }
        });
    }
}

// 나의활동 눌렀을때 로그인 안되어있으면 로그인페이지로 이동
function member_login_info() {
    $.ajax({
        url: '/layout/ck_login',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.logged_in) {
                window.location.href = "/board/board_write";
            } else {
                alert("로그인이 필요합니다.");
                window.location.href = "/login/login";
            }
        },
        error: function () {
            alert("알수없는 오류가 발생했습니다.");
        }
    });
}


