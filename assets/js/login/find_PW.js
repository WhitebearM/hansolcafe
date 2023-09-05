document.addEventListener('DOMContentLoaded', function(){
    document.getElementById('change_pw').addEventListener('keyup',checkPassword);
    document.getElementById('chage_confirm').addEventListener('keyup',checkPassword);
});

function checkPassword(){
    const check_pwInput = document.getElementById('change_pw');
    const check_confirmInput = document.getElementById('chage_confirm');
    const check_pw = check_pwInput.value;
    const check_confirm = check_confirmInput.value;
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if(!passwordPattern.test(check_pw)){
        document.getElementById('change_pw_error').textContent = '영어 대문자, 소문자, 숫자, 특수 문자를 모두 포함하고 최소 8자 이상';
        document.getElementById('change_pw_error').style.color = 'red';
    }else{
        document.getElementById('change_pw_error').textContent = '올바르게 입력하셨습니다.';
        document.getElementById('change_pw_error').style.color = 'green';  
         
        if(check_pw !== check_confirm){
            document.getElementById('change_confirm_error').textContent = '비밀번호가 일치하지 않습니다.';
            document.getElementById('change_confirm_error').style.color = 'red';
            document.getElementById('formbtn').disabled = true;
        }else{
            document.getElementById('change_confirm_error').textContent = '비밀번호가 일치합니다.';
            document.getElementById('change_confirm_error').style.color = 'green';
            document.getElementById('formbtn').disabled = false;
        }
    }
}