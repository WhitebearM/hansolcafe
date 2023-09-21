<?

class board_write extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('/board/board_write_model');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library("session");
        $this->load->library("layout_common");
        $this->load->database();

    }

    function index()
    {
        $write = 1;
        $id = $this->session->userdata("id");
        $authority = $this->session->userdata("authority");

        if ($id) {
            $data['write'] = $write;
            $data['result'] = $this->board_write_model->list_category();
            $data['authority'] = $authority;
            if(isset($_GET['category_num'])){
                $category_num = $this->input->get("category_num");
                $data['category_num'] = $category_num;
            }
            $this->load->view("/board/board_write_view", $data);
        } else {
            echo "<script>
            alert('로그인후 이용바랍니다.');
            location.href='/login/login';</script>";
        }

    }
    function modify()
    {
        
        $write = 2;
        $id = $this->session->userdata("id");
        $authority = $this->session->userdata("authority");
        $board_num = $this->input->get("board_num");

        $result = $this->board_write_model->modify_sel_board($board_num);
        if ($id) {
            if($id == $result->user_id){
                $data['write'] = $write;
                $data['result'] = $this->board_write_model->list_category();
                $data['board'] = $result;
                $data['authority'] = $authority;
                $this->load->view("/board/board_write_view", $data);
            }else{
                echo "<script>
                alert('접근 권한이 없습니다.');
                location.href='/board/board_detail?category=$result->category_num&board_num=$result->article_num';</script>";
            }
        } else {
            echo "<script>
            alert('로그인후 이용바랍니다.');
            location.href='/login/login';</script>";
        }

    }

    // 답글 이동페이지
    function reply_board_view()
    {
        $write = 3;
        $id = $this->session->userdata("id");
        $authority = $this->session->userdata("authority");
        if ($id) {
            $num = $this->input->get("num");
            $parent_board_info = $this->board_write_model->parent_board_info($num);

            $data['parent_info'] = $parent_board_info;
            $data['article_num'] = $num;
            $data['write'] = $write;
            $data['result'] = $this->board_write_model->list_category();
            $data['authority'] = $authority;
            $this->load->view("/board/board_write_view", $data);
        } else {
            echo "<script>
            alert('로그인후 이용바랍니다.');
            location.href='/login/login';</script>";
        }
    }
    function board_create()
    {
        $this->form_validation->set_rules('category_pick', 'category_pick', 'required');
        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('content', 'content', 'required');

        if ($this->form_validation->run() == false) {
            echo "<script>
            alert('빈칸으로 게시물을 등록할수 없습니다.');
            location.href='/board/board_write';</script>";
        } else {
            $this->db->trans_start();
            $id = $this->session->userdata("id");
            if ($id) {
                $gongji = $this->input->post("announcement"); //공지사항권한
                $dcsr = $this->input->post("disclosure"); // 공개범위
                if (!$this->input->post("announcement")) {
                    $gongji = 1;
                }
                if (!$this->input->post("disclosure")) {
                    $dcsr = 1;
                }
                $category = $this->input->post("category_pick"); //카테고리
                $title = $this->input->post("title"); // 제목
                $title = html_escape($title);
                $content = $this->input->post("content"); //내용
                $cleaned_content = str_replace("&nbsp;", ' ', $content);
                $cleaned_content = str_replace("<p>", ' ', $cleaned_content);
                $cleaned_content = str_replace("</p>", ' ', $cleaned_content);
                $cleaned_content = trim($cleaned_content); // 문자열 앞뒤의 공백 제거
                $length = strlen($cleaned_content);
                if ($length == 0) {
                    echo "<script>
                    alert('내용을 입력해주세요!');
                    location.href='/board/board_write';</script>";
                } else {
                    $fileupload = $this->input->post("fileupload"); //파일업로드

                    $content = str_replace(".." , "" , $content);

                    if ($gongji == "on") {
                        $gongji = 2;
                    }
                    if ($dcsr == "on") {
                        $dcsr = 2;
                    }

                    $write_board_data = $this->board_write_model->create_board($id, $gongji, $dcsr, $category, $title, $content); //게시판 업로드
                    $category_num = $write_board_data['category_num'];
                    $article_num = $write_board_data['article_num'];

                    // $original_file_name = $_FILES['file']['name'];
                    // $save_file_name = $article_num . "_" . $original_file_name;
                    $this->db->trans_complete();

                    $config['upload_path'] = './fileupload/'; //업로드 경로
                    $config['allowed_types'] = '*'; //모든 파일허용
                    $config['max_size'] = 2048; //파일 사이즈
                    $config['encrypt_name'] = false; //파일 암호화


                    //파일업로드 부분
                    if (!empty($_FILES['file']['name'])) {


                        $files = $_FILES['file'];

                        foreach ($files['name'] as $key => $filename) {
                            $this->load->library('upload', $config);

                            $_FILES['file']['name'] = $files['name'][$key];
                            $_FILES['file']['type'] = $files['type'][$key];
                            $_FILES['file']['tmp_name'] = $files['tmp_name'][$key];
                            $_FILES['file']['error'] = $files['error'][$key];
                            $_FILES['file']['size'] = $files['size'][$key];
                            if (!$this->upload->do_upload('file')) { //파일을 넣지않았거나 업로드가 안된경우
                                $data = $this->upload->data();

                            } else {
                                $data = $this->upload->data();
                                $file_path = $data['full_path']; //풀 파일경로
                                $wep_root = "C:/cloneproject/ci";
                                $file_path = str_replace($wep_root, "", $file_path);

                                $original_file_name = $_FILES['file']['name'];
                                $save_file_name = $article_num . "_" . $original_file_name;
                                // 파일업로드 테이블에 insert
                                $this->board_write_model->fileupload($article_num, $file_path, $original_file_name, $save_file_name);
                            }
                        }


                    }
                    if ($this->db->trans_status() === FALSE) {
                        echo "<script>
                        alert('알수없는 오류가 발생했습니다.');
                        location.href='/login/login';</script>";
                    }
                    echo "<script>
                    alert('글이 작성 되었습니다');
                    location.href='/board/board_detail?category=$category_num&board_num=$article_num';</script>";

                }

            } else {
                echo "<script>
                alert('오류발생');
                location.href='/login/login';</script>";
            }


        }
    }

    // 글수정
    function board_modify()
    {
        $this->form_validation->set_rules('category_pick', 'category_pick', 'required');
        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('content', 'content', 'required');



        if ($this->form_validation->run() == false) {
            echo "<script>
            alert('빈칸으로 게시물을 등록할수 없습니다.');
            location.href='/board/board_write';</script>";
        } else {
            $this->db->trans_start();
            $id = $this->session->userdata("id");
            if ($id) {
                $gongji = $this->input->post("announcement"); //공지사항권한
                $dcsr = $this->input->post("disclosure"); // 공개범위
                $board_num = $this->input->post("board_num"); //글수정 게시글 고유번호 가져오기
                if (!$this->input->post("announcement")) {
                    $gongji = 1;
                }
                if (!$this->input->post("disclosure")) {
                    $dcsr = 1;
                }

                $category = $this->input->post("category_pick"); //카테고리
                $title = $this->input->post("title"); // 제목
                $title = html_escape($title);
                $content = $this->input->post("content"); //내용

                $fileupload = $this->input->post("fileupload"); //파일업로드

                if ($gongji == "on") {
                    $gongji = 2;
                }
                if ($dcsr == "on") {
                    $dcsr = 2;
                }

                $modify_board_data = $this->board_write_model->board_modify($board_num, $gongji, $dcsr, $category, $title, $content); //게시판 업로드


                // $original_file_name = $_FILES['file']['name'];
                // $save_file_name = $board_num . "_" . $original_file_name;
                $this->db->trans_complete();

                //파일업로드 부분
                $config['upload_path'] = './fileupload/'; //업로드 경로
                $config['allowed_types'] = '*'; //모든 파일허용
                $config['max_size'] = 2048; //파일 사이즈
                $config['encrypt_name'] = false; //파일 암호화


                //파일업로드 부분
                if (!empty($_FILES['file']['name'])) {


                    $files = $_FILES['file'];

                    foreach ($files['name'] as $key => $filename) {
                        $this->load->library('upload', $config);

                        $_FILES['file']['name'] = $files['name'][$key];
                        $_FILES['file']['type'] = $files['type'][$key];
                        $_FILES['file']['tmp_name'] = $files['tmp_name'][$key];
                        $_FILES['file']['error'] = $files['error'][$key];
                        $_FILES['file']['size'] = $files['size'][$key];
                        if (!$this->upload->do_upload('file')) { //파일을 넣지않았거나 업로드가 안된경우
                            $data = $this->upload->data();
                        } else {
                            $data = $this->upload->data();
                            $file_path = $data['full_path']; //풀 파일경로
                            $wep_root = "C:/cloneproject/ci";
                            $file_path = str_replace($wep_root, "", $file_path);

                            $original_file_name = $_FILES['file']['name'];
                            $save_file_name = $board_num . "_" . $original_file_name;

                            // 파일업로드 테이블에 insert
                            $this->board_write_model->fileupload_update($board_num, $file_path, $original_file_name, $save_file_name);
                        }
                    }


                }
                if ($this->db->trans_status() === FALSE) {
                    echo "<script>
                    alert('알수없는 오류가 발생했습니다.');
                    location.href='/login/login';</script>";
                }

                echo "<script>
                alert('글수정이 완료되었습니다.');
                location.href='/board/board_detail?category=$modify_board_data->category_num&board_num=$modify_board_data->article_num';</script>";
            } else {
                echo "<script>
                alert('오류발생');
                location.href='/login/login';</script>";
            }

        }
    }


    function upload()
    {
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'C:/cloneproject/ci/uploads/';
            $uploadedFile = $uploadDir . basename($_FILES['file']['name']);

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                list($width, $height) = getimagesize($uploadedFile);

                echo json_encode([
                    'location' => base_url('uploads/') . basename($_FILES['file']['name']),
                    'width' => $width,
                    'height' => $height

                ]);
            } else {
                echo json_encode(['error' => 'File upload failed.']);
            }
        } else {
            echo json_encode(['error' => 'File upload error.']);
        }
    }

    function reply_board_write()
    {
        $this->form_validation->set_rules('category_pick', 'category_pick', 'required');
        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('content', 'content', 'required');


        if ($this->form_validation->run() == false) {
            echo "<script>
            alert('빈칸으로 게시물을 등록할수 없습니다.');
            location.href='/board/board_write';</script>";
        } else {
            $this->db->trans_start();
            $id = $this->session->userdata("id");
            if ($id) {

                $parent_num = $this->input->post("parent_num");
                $gongji = $this->input->post("announcement"); //공지사항권한
                $dcsr = $this->input->post("disclosure"); // 공개범위
                if (!$this->input->post("announcement")) {
                    $gongji = 1;
                }
                if (!$this->input->post("disclosure")) {
                    $dcsr = 1;
                }
                $category = $this->input->post("category_pick"); //카테고리
                $title = $this->input->post("title"); // 제목
                $title = html_escape($title);
                $content = $this->input->post("content"); //내용
                $fileupload = $this->input->post("fileupload"); //파일업로드

                if ($gongji == "on") {
                    $gongji = 2;
                }
                if ($dcsr == "on") {
                    $dcsr = 2;
                }

                // 답글 쓰기로 수정해야할부분
                $write_board_data = $this->board_write_model->reply_write_board($id, $gongji, $dcsr, $category, $title, $content, $parent_num); //게시판 업로드
                $category_num = $write_board_data['category_num'];
                $article_num = $write_board_data['article_num'];

                $this->db->trans_complete();

                //파일업로드 부분
                //파일업로드 부분
                $config['upload_path'] = './fileupload/'; //업로드 경로
                $config['allowed_types'] = '*'; //모든 파일허용
                $config['max_size'] = 2048; //파일 사이즈
                $config['encrypt_name'] = false; //파일 암호화
                if (!empty($_FILES['file']['name'])) {


                    $files = $_FILES['file'];

                    foreach ($files['name'] as $key => $filename) {
                        $this->load->library('upload', $config);

                        $_FILES['file']['name'] = $files['name'][$key];
                        $_FILES['file']['type'] = $files['type'][$key];
                        $_FILES['file']['tmp_name'] = $files['tmp_name'][$key];
                        $_FILES['file']['error'] = $files['error'][$key];
                        $_FILES['file']['size'] = $files['size'][$key];
                        if (!$this->upload->do_upload('file')) { //파일을 넣지않았거나 업로드가 안된경우
                            $data = $this->upload->data();

                        } else {
                            echo "업로드성공 들어가기";
                            $data = $this->upload->data();
                            $file_path = $data['full_path']; //풀 파일경로
                            $wep_root = "C:/cloneproject/ci";
                            $file_path = str_replace($wep_root, "", $file_path);

                            $original_file_name = $_FILES['file']['name'];
                            $save_file_name = $article_num . "_" . $original_file_name;
                            // 파일업로드 테이블에 insert
                            $this->board_write_model->fileupload($article_num, $file_path, $original_file_name, $save_file_name);
                        }
                    }


                }

                if ($this->db->trans_status() === FALSE) {
                    echo "<script>
                    alert('알수없는 오류가 발생했습니다.');
                    location.href='/login/login';</script>";
                }
                echo "<script>
                alert('글이 작성 되었습니다');
                location.href='/board/board_detail?category=$category_num&board_num=$article_num';</script>";


            } else {
                echo "<script>
                alert('오류발생');
                location.href='/login/login';</script>";
            }

        }
    }

}