document.addEventListener("DOMContentLoaded", function () {

    const checkbox = document.getElementById("flexCheckDefault");
    const gongjiboxs = document.querySelectorAll(".main_gongji");

    checkbox.addEventListener("click", function () {
        if (checkbox.checked) {
            gongjiboxs.forEach(function (gongjibox){
                gongjibox.style.display = "none";
            });
        } else {
            gongjiboxs.forEach(function (gongjibox){
                gongjibox.style.display = "block";
            });
        }
    });


    // 상단버튼 삭제
    $("#header_select_delete_btn").click(function () {
        var selectedBoards = [];

        $(".postcheckbox:checked").each(function () {
            selectedBoards.push($(this).val());
        });

        var selectedBoardsInput = $("<input>")
            .attr("type", "hidden")
            .attr("name", "selected_board")
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

    // 출력개수 조정
    const selectPerPage = document.getElementById("board_select_number");
    selectPerPage.addEventListener('change', function () {
        const selectValue = selectPerPage.value;
        var categoryNum = document.querySelector('.category_num').value;
        var categoryName = document.querySelector('.category_name').value;

        window.location.href = `/board/board_list/pagination?name=${categoryName}&num=${categoryNum}&selected_page=${selectValue}`;
    });

    // const selPP = document.getElementById("board_select_two");
    // selPP.addEventListener('change' , function(){
    //     const selectValue = selPP.value;
    //     var categoryNum = document.querySelector('.category_num2').value;
    //     var categoryName = document.querySelector('.category_name2').value;
    //     var seloption1 = document.querySelector('.save_option1').value;
    //     var seloption2 = document.querySelector('.save_option2').value;
    //     var seloption3 = document.querySelector('.save_option3').value;

    //     window.location.href = `/board/board_list/footer_search?category_option_1=${seloption1}&category_option_2=${seloption2}&footer_search_categoryNum=${categoryNum}&footer_search_categoryName=${categoryName}&board_footer_search=${seloption3}&selected_page=${selectValue}`;

    // });


    $('#footer_search_form').submit(function (event){
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
        var event = new Event('change' , {bubbles: true});
        checkbox.dispatchEvent(event);
    });
}






