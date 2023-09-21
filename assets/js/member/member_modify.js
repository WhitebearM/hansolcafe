

 // passwordPattern을 함수 외부에 선언 (어느곳에서도 사용하기위해)
 const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

 document.addEventListener('DOMContentLoaded', function() {
     document.getElementById('check_pw').addEventListener('input', checkFormValidity);
     document.getElementById('check_confirm').addEventListener('input', checkFormValidity);
     document.getElementById('user_email').addEventListener('input', checkFormValidity);

     document.getElementById('profilePicInput').addEventListener('change', function () {
        const previewImage = document.getElementById('previewImage');
        const profileInput = document.getElementById('profilePicInput');
        const file = this.files[0];
        const allowedExtensions = /\.(jpg|jpeg|png)$/i;
    
        if (file && allowedExtensions.test(file.name)) {
          const reader = new FileReader();
    
          reader.addEventListener('load', function () {
            previewImage.setAttribute('src', this.result);
            previewImage.style.display = 'block';
          });
    
          reader.readAsDataURL(file);
        } else {
          alert("허용하지 않은 파일 형식입니다. jpg, jpeg, png 파일만 업로드 가능합니다.");
          previewImage.setAttribute('src', '/assets/images/profile.png');
          profileInput.textContent ="다시올려주세요";
        }
      });
 });

 function checkFormValidity() {
     const ck_pwInput = document.getElementById('check_pw');
     const ck_confirmInput = document.getElementById('check_confirm');
     const ck_pw = ck_pwInput.value;
     const ck_confirm = ck_confirmInput.value;

     const isPasswordValid = passwordPattern.test(ck_pw);
     const isConfirmValid = ck_pw === ck_confirm;
     const isEmailValid = checkEmailValidity();

    //  변수로 이용해 삼항연산자씀
     document.getElementById('change_pw_error').textContent = isPasswordValid
         ? '비밀번호를 정상적으로 입력하셨습니다.' : '영어 대문자, 소문자, 숫자, 특수 문자를 모두 포함하고 최소 8자 이상';
     document.getElementById('change_pw_error').style.color = isPasswordValid ? 'green' : 'red';

     document.getElementById('change_confirm_error').textContent = isConfirmValid
     ? (ck_confirm === '' ? '' : '비밀번호가 일치합니다.')
     : '';
 document.getElementById('change_confirm_error').style.color = isConfirmValid || ck_confirm === '' ? 'green' : 'red';

     document.getElementById('change_email_error').textContent = isEmailValid
         ? '정확히 입력하셨습니다.' : '올바른 이메일 형식이 아닙니다.';
     document.getElementById('change_email_error').style.color = isEmailValid ? 'green' : 'red';

     // 모든 유효성 검사를 통과한 경우에만 버튼 활성화
     const isAllValid = isConfirmValid && isEmailValid;
     document.getElementById('modifybtn').disabled = !isAllValid;
 }

 function checkEmailValidity() {
     const userEmailInput = document.getElementById('user_email');
     const userEmail = userEmailInput.value;
     const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
     return emailPattern.test(userEmail);
 }