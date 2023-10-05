
document.addEventListener('DOMContentLoaded', function () {
  const registerForm = document.getElementById("main_form");
  const registerButton = document.getElementById("formbtn");

  // registerButton.style.display = 'none';

  document.getElementById('profilePicInput').addEventListener('change', function () {
    const previewImage = document.getElementById('previewImage');
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
      // previewImage.style.display = 'none';
    }
  });

  document.getElementById('user_pw').addEventListener('keyup', function () {
    checkPasswordMatch();
    checkFormValidity();
  });

  // 비밀번호 확인 입력값 변경 이벤트 등록
  document.getElementById('user_pw_verify').addEventListener('keyup', function () {
    checkPasswordMatch();
    checkFormValidity();
  });

  // 이메일 입력값 변경 이벤트 등록
  document.getElementById('user_email').addEventListener('keyup', function () {
    checkEmailFormat();
    checkFormValidity();
  });

  // 이름 입력값 변경 이벤트 등록
  document.getElementById('user_name').addEventListener('keyup', function () {
    checkName();
    checkFormValidity();
  });

  //피드백 추가부분 disabled가 아닌 preventDefault로 막은뒤에 유효성검사 결과로 조건판단
  registerForm.addEventListener('submit', function(event){
    if(!checkFormValidity()){
      event.preventDefault();
    }else{
      registerForm.submit();
    }
  });

  // 폼 입력 값 변경 이벤트 등록
  function checkFormValidity() {
    const user_id_error = document.getElementById('userid_error').textContent;
    const user_pw_error = document.getElementById('userpw_error').textContent;
    const user_confirm_error = document.getElementById('userpw_verify_error').textContent;
    const user_email_error = document.getElementById('useremail_error').textContent;
    const user_name_error = document.getElementById('username_error').textContent;

    const isFormValid =
      user_id_error === '사용 가능한 아이디 입니다.' &&
      user_pw_error === '올바른 비밀번호입니다.' &&
      user_confirm_error === '비밀번호가 일치합니다!' &&
      user_email_error === '정확히 입력하셨습니다.' &&
      user_name_error === '정확히 입력하셨습니다.';

    return isFormValid; 
   
  }

  // 아이디 중복확인
  $("#idck").click(function () {
    const userid = $("#user_id").val();

    if (userid.trim() === '') {
      alert("아이디를 입력해주세요!");
    } else {
      $.ajax({
        type: "POST",
        url: "/member/memberform/idck",
        data: { userid: userid },
        dataType: "json",
        success: function (data) {
          if (data.message === 'duplicate') {
            $("#userid_error").text("이미 사용중인 아이디 입니다.");
            $("#userid_error").css("color", "red");

          } else if (data.message === 'available') {
            $("#userid_error").text("사용 가능한 아이디 입니다.");
            $("#userid_error").css("color", "green");

          } else {
            alert("알수없는 오류가 발생했습니다.");
          }
        },
        error: function (error) {
          alert("서버와 통신하는중 오류가 발생했습니다.");
          console.log(error);
        }
      });
    }
  });

  let emailTimeout = null; // 타이머 변수

  $("#user_email").on('keyup', function () {
    clearTimeout(emailTimeout);//이전타이머제거

    //이메일 중복확인함수
    emailTimeout = setTimeout(checkEmailValidity, 1);
  });

  function checkEmailValidity() {
    const userEmail = $("#user_email").val();

    if (userEmail.trim() === '') {
      $("#useremail_error").text("이메일을 입력해주세요");
      $("#useremail_error").css("color", "red");
    } else {
      $.ajax({
        type: "POST",
        url: "/member/memberform/emailck",
        data: { user_email: userEmail },
        dataType: "json",
        success: function (data) {
          if (data.message == 'duplicate') {
            $("#useremail_error").text("이미 사용중인 이메일 입니다.");
            $("#useremail_error").css("color", "red");
          }
          else if (data.message === 'avilable') {
            $("#useremail_error").text(checkEmailFormat);
          } else {
            alert("알수없는 오류가 발생했습니다.");
          }
        },
        error: function (error) {
          alert("서버와 통신하는중 오류가 발생했습니다.");
        }

      });
    }
  }

    // 패스워드 암호화 복호화
    var password_Toggle = document.getElementById('password_toggle');

    password_Toggle.addEventListener('click',function(event){
      event.preventDefault();

      var password_Input = document.getElementById('user_pw');

      if (password_Input.type === "password") {
        password_Input.type = "text";
        password_Toggle.innerHTML = '<img src="/assets/images/none-password.png" width="25" height="25">';
      } else {
        password_Input.type = "password";
        password_Toggle.innerHTML = '<img src="/assets/images/password.png" width="25" height="25">';
      }
    });

    
  

});

//올바른 비밀번호형식인지 비밀번호가 일치하는지 검사
function checkPasswordMatch() {
  const userpwInput = document.getElementById('user_pw');
  const userpwVerifyInput = document.getElementById('user_pw_verify');
  const userpw = userpwInput.value;
  const userpwVerify = userpwVerifyInput.value;
  const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

  if (!passwordPattern.test(userpw)) {
    document.getElementById('userpw_error').textContent = '영어 대문자, 소문자, 숫자, 특수 문자를 모두 포함하고 최소 8자 이상';
    document.getElementById('userpw_error').style.color = 'red';
  } else {
    document.getElementById('userpw_error').textContent = '올바른 비밀번호입니다.';
    document.getElementById('userpw_error').style.color = 'green';
    if (userpw !== userpwVerify) {
      document.getElementById('userpw_verify_error').textContent = "비밀번호가 일치 하지 않습니다.";
      document.getElementById('userpw_verify_error').style.color = 'red';
    } else if (userpw === '' || userpw === null) {
      document.getElementById('userpw_verify_error').textContent = "비밀번호를 입력해주세요.";
    } else {
      document.getElementById('userpw_verify_error').textContent = '비밀번호가 일치합니다!';
      document.getElementById('userpw_verify_error').style.color = 'green';
    }
  }
}
//올바른 이메일 형식인지 확인한다.
function checkEmailFormat() {
  const userEmailInput = document.getElementById('user_email');
  const userEmail = userEmailInput.value;
  const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  const user_email = document.getElementById('useremail_error');

  if (!emailPattern.test(userEmail)) {
    user_email.textContent = "올바른 이메일형식이 아닙니다.";
    user_email.style.color = "red";
  } else {
    user_email.textContent = "정확히 입력하셨습니다.";
    user_email.style.color = "green";
  }
}
function checkName() {
  const userNameInput = document.getElementById('user_name');
  const userName = userNameInput.value;
  const namePattern = /^[a-zA-Z가-힣]{2,}$/;
  const user_name = document.getElementById('username_error');

  if (userName.trim() === '') {
    user_name.textContent = "이름을 입력해주세요!";
    user_name.style.color = "red";
  } else if (!namePattern.test(userName)) {
    user_name.textContent = "한글과 영어만 사용가능하고 2글자 이상입니다.";
    user_name.style.color = "red";
  } else {
    user_name.textContent = '정확히 입력하셨습니다.';
    user_name.style.color = "green";
  }
}
