document.addEventListener('DOMContentLoaded', function () {

    $('#main_footer_search_form').submit(function (event) {
        event.preventDefault();

        var searchText = $('#main_footer_go').val();

        var sanitizedText = searchText.replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\s]/gi, '');

        $('#main_footer_go').val(sanitizedText);

        $('#main_footer_search_form').get(0).submit();
    });

    //캘린더로 게시글검색
    $('#main_search_date').on("change", function () {
        const selectDate = $('#main_search_date').val();

        window.location.href = `/layout/full_board_list_date?date=${selectDate}`;
    });
});

function changedatePerpage(search_selectPerPage) {
    const selectValue = date_select_search_number.value;
    var sel_date = document.querySelector('.select_date_result').value;

    window.location.href = `/board/board_list/date_search?&search_date=${sel_date}&selected_page=${selectValue}`;
}


