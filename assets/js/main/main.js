document.addEventListener('DOMContentLoaded' ,function(){

    $('#main_footer_search_form').submit(function (event) {
        event.preventDefault();

        var searchText = $('#main_footer_go').val();
        
        var sanitizedText = searchText.replace(/[\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\s]/gi, '');

        $('#main_footer_go').val(sanitizedText);

        $('#main_footer_search_form').get(0).submit();
    });
});
