document.addEventListener("DOMContentLoaded", function () {

    const checkbox = document.getElementById("flexCheckDefault");
    const gongjiboxs = document.querySelectorAll(".main_gongji");

    checkbox.addEventListener("click", function () {
        if (checkbox.checked) {
            gongjiboxs.forEach(function (gongjibox) {
                gongjibox.style.display = "none";
            });
        } else {
            gongjiboxs.forEach(function (gongjibox) {
                gongjibox.style.display = "block";
            });
        }
    });

    // 답게시글 숨기기
    document.querySelectorAll(".reply_link").forEach(function (link) {
        link.addEventListener("click", function (event) {
            event.preventDefault();

            // 클릭한 링크의 grp와 depth 값을 가져옵니다.
            var clickedGrp = this.getAttribute("data-grp");
            var clickedDepth = this.getAttribute("data-depth");
            // 모든 자식 게시물을 가져옵니다.
            var childComments = document.querySelectorAll(".board_list");

            // 같은 grp 값을 가진 자식 게시물 중 depth가 clickedDepth보다 큰 것을 숨깁니다.
            childComments.forEach(function (comment) {
                var commentGrp = comment.querySelector(".reply_linker").getAttribute("data-grp");
                var commentDepth = comment.querySelector(".reply_linker").getAttribute("data-depth");
                var commentDisplay = comment.style.display;
                
                // 같은 grp 값이지만 depth가 clickedDepth보다 큰 경우 숨깁니다.
                if (commentGrp === clickedGrp && parseInt(commentDepth) > parseInt(clickedDepth)) {
                    if(comment.style.display === "none"){
                        comment.style.display = "block";
                        link.textContent = "답글 숨기기 ▲";
                    }else{
                        comment.style.display = "none";
                        link.textContent = "답글 숨기기 ▼";
                    }
                }
            });
        });
    });


    // 상단버튼 삭제
    $("#header_select_delete_btn").click(function () {
        var selectedBoards = [];

        $(".postcheckbox:checked").each(function () {
            selectedBoards.push($(this).val());
        });

        // var checkboxs = document.querySelectorAll(".postcheckbox");

        // // NodeList를 배열로 변환하여 각 체크박스를 순회
        // Array.from(checkboxs).forEach(function (checkbox){
        //     if(checkbox.checked){
        //         selectedBoards.push(checkbox.value);
        //     }
        // });

        // 새로운태그를 만듦
        var selectedBoardsInput = $("<input>")
            .attr("type", "hidden")
            .attr("name", "selected_board[]")
            .val(selectedBoards.join(","));

        $("#select_delete_form").append(selectedBoardsInput);

        // 폼 제출
        $("#select_delete_form").submit();
    });


    //체크후 카테고리이동
    var selectedArticleNum = [];

    $(".postcheckbox").on("change", function () {

        var articleNum = $(this).val();

        if ($(this).is(":checked")) {
            selectedArticleNum.push(articleNum);
        } else {
            var index = selectedArticleNum.indexOf(articleNum);
            if (index !== -1) {
                selectedArticleNum.splice(index, 1);
            }
        }
    });

    // 카테고리 이동 1 ~ 2
    $("#category_modify1").on("click", function () {
        $("#sel_modify_form").append(
            $("<input>")
                .attr("type", "hidden")
                .attr("name", "selected_article")
                .val(JSON.stringify(selectedArticleNum))
        );
    });
    $("#category_modify2").on("click", function () {
        $("#sel_modify_form").append(
            $("<input>")
                .attr("type", "hidden")
                .attr("name", "selected_article")
                .val(JSON.stringify(selectedArticleNum))
        );
    });

    // 하단 검색창 특수문자 자르기
    $('#footer_search_form').submit(function (event) {
        event.preventDefault();

        var searchText = $('#footer_search_gogo').val();

        var sanitizedText = searchText.replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\s]/gi, '');

        $('#footer_search_gogo').val(sanitizedText);

        $('#footer_search_form').get(0).submit();
    });

});

// 체크박스 전체선택
function selected_ck(selectAll) {
    const checkboxes = document.querySelectorAll('.postcheckbox');

    checkboxes.forEach((checkbox) => {

        checkbox.checked = selectAll.checked;
        var event = new Event('change', { bubbles: true });
        checkbox.dispatchEvent(event);
    });
}

// 기본 페이지네이션
function changePerpage(selectPerPage) {
    const selectValue = selectPerPage.value;
    var categoryNum = document.querySelector('.category_num').value;
    var categoryName = document.querySelector('.category_name').value;

    window.location.href = `/board/board_list/pagination?name=${categoryName}&num=${categoryNum}&selected_page=${selectValue}`;
}

// 검색결과 페이지네이션
function changeSearchPerpage(search_selectPerPage) {
    const selectValue = search_selectPerPage.value;
    var categoryNum = document.querySelector('.category_num').value;
    var categoryName = document.querySelector('.category_name').value;
    var option1 = document.querySelector('.option1').value;
    var option2 = document.querySelector('.option2').value;
    var option3 = document.querySelector('.option3').value;

    window.location.href = `/board/board_list/footer_search?category_option_1=${option1}&category_option_2=${option2}&footer_search_categoryNum=${categoryNum}&footer_search_categoryName=${categoryName}&board_footer_search=${option3}&selected_page=${selectValue}`;
}






