document.addEventListener('DOMContentLoaded', function () {

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
      profileInput.textContent = "다시올려주세요";
    }
  });

  // 비밀번호 유효성 검사 패턴
  const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

  // 비밀번호 입력 필드
  const ck_pwInput = document.getElementById('check_pw');
  const ck_confirmInput = document.getElementById('check_confirm');

  // 비밀번호 유효성 검사 이벤트 리스너
  ck_pwInput.addEventListener('input', function () {
    const ck_pw = ck_pwInput.value;
    const ck_confirm = ck_confirmInput.value;
    const isPasswordValid = passwordPattern.test(ck_pw);

    // 비밀번호 유효성 메시지 표시
    const pwError = document.getElementById('change_pw_error');
    pwError.textContent = isPasswordValid
      ? '비밀번호를 정상적으로 입력하셨습니다.'
      : '영어 대문자, 소문자, 숫자, 특수 문자를 모두 포함하고 최소 8자 이상';
    pwError.style.color = isPasswordValid ? 'green' : 'red';

  });

  // 비밀번호 확인 이벤트 리스너
  ck_confirmInput.addEventListener('input', function () {
    const ck_pw = ck_pwInput.value;
    const ck_confirm = ck_confirmInput.value;
    const isPasswordValid = passwordPattern.test(ck_pw);

    // 비밀번호 확인 메시지 표시
    const confirmError = document.getElementById('change_confirm_error');
    confirmError.textContent = (ck_confirm !== '' && ck_pw === ck_confirm)
      ? '비밀번호가 일치합니다.'
      : '공백이거나 비밀번호가 일치하지 않습니다';
    confirmError.style.color = (ck_confirm !== '' && ck_pw === ck_confirm) ? 'green' : 'red';

  });

  const userEmailInput = document.getElementById('user_email');
  const modifybtn = document.getElementById('modifybtn');
  const emailError = document.getElementById('change_email_error');

  userEmailInput.addEventListener('input', function () {
    const userEmail = userEmailInput.value;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const isEmailValid = emailPattern.test(userEmail);

    emailError.textContent = isEmailValid
      ? '정확히 입력하셨습니다.'
      : '올바른 이메일 형식이 아닙니다.';
    emailError.style.color = isEmailValid ? 'green' : 'red';

    // 패턴 검사 통과 및 중복 확인 요청 전에 버튼 비활성화
    modifybtn.disabled = true;

    if (isEmailValid) {
      // 서버에 이메일 중복 확인 요청을 보냄
      $.ajax({
        type: 'POST',
        url: '/member/member_modify/check_Email',
        data: { user_email: userEmail },
        dataType: 'json',
        success: function (response) {
          if (response.isDuplicate) {
            emailError.textContent = '이미 사용 중인 이메일 주소입니다.';
            emailError.style.color = 'red';
          } else {
            emailError.textContent = '사용 가능한 이메일 주소입니다.';
            emailError.style.color = 'green';

            // 모든 유효성 검사를 통과하고 이메일이 중복되지 않는 경우에만 버튼 활성화
            modifybtn.disabled = false;
          }
        },
        error: function () {
          alert('이메일 중복확인체크에 실패하셨습니다.');
        },
      });
    }
  });
});