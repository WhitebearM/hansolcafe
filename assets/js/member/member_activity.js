function show_div(div_id){
    var board = document.getElementById('activity_write_mini_board');
    var comment = document.getElementById('activity_write_comment');
    var delete_comment = document.getElementById('activity_delete_board');

    var btn1 = document.getElementById("activity_btn1");
    var btn2 = document.getElementById("activity_btn2");
    var btn3 = document.getElementById("activity_btn3");

    if(div_id === 'activity_write_mini_board'){
        board.style.display = 'block';
        btn1.style.backgroundColor = 'gray';
        comment.style.display = 'none';
        btn2.style.backgroundColor = 'gainsboro';
        delete_comment.style.display = 'none';
        btn3.style.backgroundColor = 'gainsboro';
    }
    else if(div_id === 'activity_write_comment'){
        board.style.display = 'none';
        btn1.style.backgroundColor = 'gainsboro';
        comment.style.display = 'block';
        btn2.style.backgroundColor = 'gray';
        delete_comment.style.display = 'none';
        btn3.style.backgroundColor = 'gainsboro';
    }
    else if(div_id === 'activity_delete_board'){
        board.style.display = 'none';
        btn1.style.backgroundColor = 'gainsboro';
        comment.style.display = 'none';
        btn2.style.backgroundColor = 'gainsboro';
        delete_comment.style.display = 'block';
        btn3.style.backgroundColor = 'gray';
        
    }

}

function selectAllboard(selectAll){
    const checkboxes = document.querySelectorAll('.checkbox_board');
   
    checkboxes.forEach((checkbox) => {
        checkbox.checked = selectAll.checked;
    });
}

function selectAllComment(selectAll){
    const checkboxes = document.querySelectorAll('.checkbox_comments');
   
    checkboxes.forEach((checkbox) => {
        checkbox.checked = selectAll.checked;
    });
}

function selectAlldelete(selectAll){
    const checkboxes = document.querySelectorAll('.checkbox_delete');
   
    checkboxes.forEach((checkbox) => {
        checkbox.checked = selectAll.checked;
    });
}


