$(document).ready(function () {
    //카테고리이동
    $('#category_modify').click(function () {
        var selectedCategory = $('#modify_list').val();
        var article_num = $('#modify_article_num').val();

        $.ajax({
            type: "POST",
            url: "/board/board_detail/category_modify",
            data: {
                category_id: selectedCategory,
                article_num: article_num
            },
            success: function (response) {
                alert("카테고리가 이동 되었습니다.");
                $('#staticBackdrop_k').modal('hide');
                window.location.href = "/layout";
            },
            error: function () {
                alert("카테고리 이동에 실패했습니다.");
            }
        });
    });



    // 공지글로 변경
    $("#gongji_btn").click(function () {
        var changeMainStatus = $('#detail_article_num').val();

        if (confirm("공지를 변경 하시겠습니까?")) {
            $.ajax({
                type: "POST",
                url: "/board/board_detail/change_main_status",
                data: { article_num: changeMainStatus },
                success: function (response) {
                    location.reload();
                },
                error: function () {
                    alert("알수없는 오류가 발생했습니다.");
                }
            });
        }
    });
    // 댓글작성
/*     $("#detail_comments_write").submit(function (event) {
        event.preventDefault();
        var commentText = $('#detail_comments_write_text').val();
        var article_Num = $('#comment_article_num').val();
        var user_Id = $('#comment_user_id').val();
        var parent_Id = $('#comment_post_parent_id').val();
        var detail_comment_file = $('#detail_comments_file').val();

        $.ajax({
            type: "POST",
            url: "/board/board_detail/add_comment",
            data: {
                commenttext: commentText,
                article_num: article_Num,
                user_id: user_Id,
                parent_Id: parent_Id,
                detail_comment_file: detail_comment_file
            },
            success: function (response) {
                location.reload();
            },
            error: function () {
                alert("댓글 등록중 오류가 발생했습니다.");
            }
        });
    }); */

    // 댓글 삭제
    $(".detail_comments_sub_btn2").click(function (event) {
        event.preventDefault();
        var commentContainer = $(this).closest(".comment-container");
        var comment_number = commentContainer.find(".comment_num").val();

        if (confirm("댓글을 삭제 하시겠습니까?")) {
            $.ajax({
                type: "POST",
                url: "/board/board_detail/comment_delete",
                data: {
                    delete_comment: comment_number
                },
                success: function (response) {
                    location.reload();
                },
                error: function () {
                    alert("알수없는 오류가 발생했습니다.");
                }
            });
        }
    });

    // 좋아요
    $(".heart_up").each(function () {
        var $this = $(this); //현재 클릭된 버튼의 DOM요소를 나타냄(html)
        var articleNum = $this.data("article");

        $this.on('click', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: "/board/board_detail/heart_up",
                data: { article_num: articleNum },
                success: function (response) {
                    if (response.success) {
                        if (response.isLiked) {
                            $this.children("img").attr("src", "/assets/images/full_heart.png");
                        } else {
                            $this.children("img").attr("src", "/assets/images/null_heart.png");
                        }
                        $this.siblings(".heart_count").text(response.heartCount);
                    }
                }
            });
        });
    });

    // 답게시글 달기
    $(".reply_board").click(function (event) {

        var detail_article_num = $('#detail_article_num').val();

        window.location.href = '/board/board_write/reply_board_view?num=' + detail_article_num;
    });

    //댓글 이미지 첨부
    document.getElementById('detail_comments_file').addEventListener('change', function () {
        const previewImage = document.getElementById('detail_previewImage');
        const file = this.files[0];
        const allowedExtensions = /\.(jpg|jpeg|png|gif)$/i;
    
        if (file && allowedExtensions.test(file.name)) {
            const reader = new FileReader();
    
            reader.addEventListener('load', function () {
                previewImage.innerHTML = `<img src="${this.result}" alt="Uploaded Image" />`;
                previewImage.style.display = 'block';
                previewImage.style.maxWidth = '100px';
                previewImage.style.height = '100px';
            });
    
            reader.readAsDataURL(file);
        } else {
            alert("허용하지 않은 파일 형식입니다. jpg, jpeg, png, gif 파일만 업로드 가능합니다.");
            previewImage.innerHTML = ''; // 이미지 제거
            previewImage.style.display = 'none';
        }
    });
    
});

// 댓글 열리고 닫기
function toggleEditSection(comment_num) {
    var editSection = document.getElementById('edit-comment-section-' + comment_num);

    if (editSection.style.display === 'none') {
        editSection.style.display = 'block';
    } else {
        editSection.style.display = 'none';
    }
}
// 답글 열리고 닫기
function toggle_recomment_btn(e, comment_num) {
    e.preventDefault();

    var recommentSection = document.getElementById('edit-recomment-section-' + comment_num);

    if (recommentSection.style.display === 'none') {
        recommentSection.style.display = 'block';
    } else {
        recommentSection.style.display = 'none';
    }
}


function comment_update($comment_num) {
    var category_num = document.querySelector('#category_num' + $comment_num).value;
    var article_number = document.querySelector('#article_num' + $comment_num).value;
    var detail_comment_num = document.querySelector('#detail_comment_num' + $comment_num).value;
    var detail_comment_content = document.querySelector('#detail_comment_content' + $comment_num).value;

    $.ajax({
        type: "POST",
        url: "/board/board_detail/board_comment_update",
        data: {
            category_num: category_num,
            article_number: article_number,
            detail_comment_num: detail_comment_num,
            detail_comment_content: detail_comment_content
        },
        success: function (response) {
            var category = response.category_num;
            var board = response.board_num;
            var url = `/board/board_detail?category=${category}&board_num=${board}`;
            window.location.href = url;
        }

    });

}

function comments_reupdate(value, e) {
    e.preventDefault();
    var boardNum = $('#detail_article_num').val();
    var categoryNum = $('#board_detail_category_num').val();
    var valueSel = value;

    window.location.href = '/board/board_detail?category=' + categoryNum + '&board_num=' + boardNum + '&comments_val=' + valueSel;
}

