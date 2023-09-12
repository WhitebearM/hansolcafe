document.addEventListener('DOMContentLoaded', function(){
   document.getElementById("hi").addEventListener('click' , function(e){
        var con = confirm("회원을 탈퇴 하시겠습니까?");
        var form = document.getElementById("delete_mem");

        if(con){
            form.submit();
        }else{

        }
   });
});