// tinymce 에디터
document.addEventListener("DOMContentLoaded", function () {
    tinymce.init({
        selector: '#f_content',
        width: 1260,
        images_upload_url: '/upload',//컨트롤러
        images_upload_base_path: '/uploads',//실제 업로드 되는위치
        file_picker_types: 'file image media',
        image_uploadtab: false, // 이미지 업로드 탭 비활성화
        extended_valid_elements: 'script[src|async|defer|type|charset]',
        images_max_dimensions: {
            width: 800, // 최대 이미지 폭
            height: 600 // 최대 이미지 높이
        },



        file_picker_callback: function (callback, value, meta) {
            // 파일 선택 창을 열기 위해 임시 input 태그 생성
            const input = document.createElement('input');
            // 생성한 input 태그의 타입을 파일로 설정하여 파일 선택 창으로 동작하도록 함
            input.setAttribute('type', 'file');
            // 이미지 파일만 선택 가능하도록 accept 속성 설정
            input.setAttribute('accept', 'image/*');

            // input 태그에 change 이벤트 리스너 추가
            input.addEventListener('change', function () {
                // 선택한 파일 가져오기
                const file = input.files[0];

                // 파일 업로드 로직 구현
                const formData = new FormData();
                formData.append('file', file);

                // 서버로 파일 업로드 요청 보내기
                fetch('/board/board_write/upload', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.location) {
                            // 파일 업로드 성공 시, callback 함수를 호출하여 에디터에 이미지 삽입
                            callback(data.location, {
                                text: 'alt text',
                                width: data.width,
                                height: data.height
                            });
                        } else {
                            console.error('File upload failed:', data.error);
                            alert("실패하였습니다.");
                        }
                    })
                    .catch(error => {
                        console.error('File upload error:', error);
                        alert("실패하였습니다.2");
                    });
            });

            // 파일 선택 창 열기 위해 input 태그를 클릭
            input.click();
        },
        plugins: 'image link table code',
        toolbar: 'image | bold italic underline | numlist bullist | link image | table | code',


    });


    //파일 사이즈가 너무 클때
    document.getElementById("fileupload").addEventListener('change', function () {
        const fileInput = document.getElementById("fileupload");
        const maxSize = 2 * 1024 * 1024;

        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (file.size > maxSize) {
                document.getElementById("fileSizeError").style.display = 'block';
                document.getElementById("fileSizeError").style.color = 'red';
                fileInput.value = "";
            } else {
                document.getElementById("fileSizeError").style.display = 'none';
            }
        }
    });

    
    document.querySelectorAll('.delete-button').forEach(function (button) {
        button.addEventListener('click', function () {
            var fileNumToDelete = this.getAttribute('data-file-num');

            // ori_file 배열에서 해당 값 제거
            var oriFileArray = document.querySelectorAll('input[name="ori_file[]"]');
            for (var i = 0; i < oriFileArray.length; i++) {
                if (oriFileArray[i].value == fileNumToDelete) {
                    oriFileArray[i].remove(); // 해당 input 요소 삭제
                    break;
                }
            }

            console.log(oriFileArray.values())
            // 화면에서 해당 요소 제거
            var parentElement = this.parentNode;
            parentElement.remove();
        });
    });

});


//돌아가는함수
function goBack() {
    window.history.back();
}

// 파일첨부 했을때 파일이름 출력
function fileName() {
    const fileInput = document.getElementById("fileupload"); //file 업로드부분 가져오기
    const fileList = document.getElementById("file_list"); //파일 이름 출력부분


    fileSizeError.style.display = "none"; // 파일 크기 오류 메시지 초기화
    fileList.innerHTML = ""; // 파일 목록 초기화

    for (let i = 0; i < fileInput.files.length; i++) {
        const file = fileInput.files[i];

        const fileItem = document.createElement("div");
        fileItem.textContent = file.name;

        const deleteButton = document.createElement("button");
        deleteButton.textContent = "x";
        deleteButton.onclick = function () {
            // 파일 삭제 버튼 클릭 시 해당 파일을 목록에서 제거
            fileList.removeChild(fileItem);

            // FileList 객체에서 파일을 제거
            const newFileList = new DataTransfer();
            for (let j = 0; j < fileInput.files.length; j++) {
                if (i !== j) {
                    newFileList.items.add(fileInput.files[j]);
                }
            }
            fileInput.files = newFileList.files;
        };

        fileItem.appendChild(deleteButton);
        fileList.appendChild(fileItem);
    }

}

// 게시판 작성 미리보기
function showPreview(e) {
    var title = document.getElementById("title").value;
    var content = tinymce.get("f_content").getContent();

    // var content = document.getElementById("f_content").value;


    var previewWindow = window.open('', '미리보기', 'width=800,height=800');

    previewWindow.document.write('<html>'
        + '<head>'
        + ' <title>미리보기</title>'
        + '</head>'
        + '<body style="text-align:center;">'
        + '<h3 style="margin:100px auto 180px auto;">' + title + '</h3>'
        + '<p>' + content + '</p>'
        + '</body>'
        + '</html>');
}
