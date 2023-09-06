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
        if ($id) {
            $data['write'] = $write;
            $data['result'] = $this->board_write_model->list_category();
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
        $board_num = $this->input->get("board_num");

        $result = $this->board_write_model->modify_sel_board($board_num);
        if ($id) {
            $data['write'] = $write;
            $data['result'] = $this->board_write_model->list_category();
            $data['board'] = $result;
            $this->load->view("/board/board_write_view", $data);
        } else {
            echo "<script>
            alert('로그인후 이용바랍니다.');
            location.href='/login/login';</script>";
        }

    }

    // 답글 이동페이지
    function reply_board_view(){
        $write = 3;
        $id = $this->session->userdata("id");
        if ($id) {
            $num = $this->input->get("num");
            $parent_board_info = $this->board_write_model->parent_board_info($num);

            $data['parent_info'] = $parent_board_info;
            $data['article_num'] = $num;
            $data['write'] = $write;
            $data['result'] = $this->board_write_model->list_category();
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

        $config['upload_path'] = './fileupload/'; //업로드 경로
        $config['allowed_types'] = '*'; //모든 파일허용
        $config['max_size'] = 2048; //파일 사이즈
        $config['encrypt_name'] = false; //파일 암호화


        if ($this->form_validation->run() == false) {
            echo "<script>
            alert('오류가 발생하였습니다.');
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
                $content = $this->input->post("content"); //내용
                $fileupload = $this->input->post("fileupload"); //파일업로드

                if ($gongji == "on") {
                    $gongji = 2;
                }
                if ($dcsr == "on") {
                    $dcsr = 2;
                }

                $write_board_data = $this->board_write_model->create_board($id, $gongji, $dcsr, $category, $title, $content); //게시판 업로드
                $category_num = $write_board_data['category_num'];
                $article_num = $write_board_data['article_num'];
                
                $original_file_name = $_FILES['file']['name'];
                $save_file_name = $article_num."_".$original_file_name;
                $this->db->trans_complete();

                //파일업로드 부분
                if (!empty($_FILES['file']['name'])) {
                    
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload('file')) { //업로드에 실패한경우
                        $data = $this->upload->data();
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        echo "업로드성공 들어가기";
                        $data = $this->upload->data();
                        $file_path = $data['full_path'];//풀 파일경로

                        // 파일업로드 테이블에 insert
                        $this->board_write_model->fileupload($article_num,$file_path,$original_file_name,$save_file_name);
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

    // 글수정
    function board_modify()
    {
        $this->form_validation->set_rules('category_pick', 'category_pick', 'required');
        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('content', 'content', 'required');

        $config['upload_path'] = './fileupload/'; //업로드 경로
        $config['allowed_types'] = '*'; //모든 파일허용
        $config['max_size'] = 2048; //파일 사이즈
        $config['encrypt_name'] = false; //파일 암호화


        if ($this->form_validation->run() == false) {
            echo "<script>
            alert('오류가 발생하였습니다.');
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
                $content = $this->input->post("content"); //내용
                $fileupload = $this->input->post("fileupload"); //파일업로드

                if ($gongji == "on") {
                    $gongji = 2;
                }
                if ($dcsr == "on") {
                    $dcsr = 2;
                }

                $modify_board_data = $this->board_write_model->board_modify($board_num, $gongji, $dcsr, $category, $title, $content); //게시판 업로드
                

                $original_file_name = $_FILES['file']['name'];
                $save_file_name = $board_num."_".$original_file_name;
                $this->db->trans_complete();

                //파일업로드 부분
                if (!empty($_FILES['file']['name'])) {
                    
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload('file')) { //업로드에 실패한경우
                        $data = $this->upload->data();
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        echo "업로드성공 들어가기";
                        $data = $this->upload->data();
                        $file_path = $data['full_path'];//풀 파일경로

                        // 파일업로드 테이블에 update
                        $this->board_write_model->fileupload_update($board_num,$file_path,$original_file_name,$save_file_name);
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

    function reply_board_write(){
        $this->form_validation->set_rules('category_pick', 'category_pick', 'required');
        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('content', 'content', 'required');

        $config['upload_path'] = './fileupload/'; //업로드 경로
        $config['allowed_types'] = '*'; //모든 파일허용
        $config['max_size'] = 2048; //파일 사이즈
        $config['encrypt_name'] = false; //파일 암호화


        if ($this->form_validation->run() == false) {
            echo "<script>
            alert('오류가 발생하였습니다.');
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
                $content = $this->input->post("content"); //내용
                $fileupload = $this->input->post("fileupload"); //파일업로드

                if ($gongji == "on") {
                    $gongji = 2;
                }
                if ($dcsr == "on") {
                    $dcsr = 2;
                }

                // 답글 쓰기로 수정해야할부분
                $write_board_data = $this->board_write_model->reply_write_board($id, $gongji, $dcsr, $category, $title, $content,$parent_num); //게시판 업로드
                $category_num = $write_board_data['category_num'];
                $article_num = $write_board_data['article_num'];
                
                $original_file_name = $_FILES['file']['name'];
                $save_file_name = $article_num."_".$original_file_name;
                $this->db->trans_complete();

                //파일업로드 부분
                if (!empty($_FILES['file']['name'])) {
                    
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload('file')) { //업로드에 실패한경우
                        $data = $this->upload->data();
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                    } else {
                        echo "업로드성공 들어가기";
                        $data = $this->upload->data();
                        $file_path = $data['full_path'];//풀 파일경로

                        // 파일업로드 테이블에 insert
                        $this->board_write_model->fileupload($article_num,$file_path,$original_file_name,$save_file_name);
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