<?

class layout extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("layout_model");
        $this->load->library("session");
        $this->load->library('layout_common');
        $this->load->library('user_agent');
        $this->load->library("encryption");
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->helper('url');
    }

    public function index()
    {
        $ct_num = 1;
        $result = $this->layout_model->main_list();

        $this->layout_common->view(
            "/main/main_view",
            array(
                "result" => $result,
                "ct_num" => $ct_num
            )
        );

    }

    public function full_board_list()
    {
        $ct_num = 2;

        $config['base_url'] = base_url("/layout/full_board_list");
        $config['uri_segment'] = 4;
        //url정상적으로 저장됨
        $config['total_rows'] = $this->layout_model->layout_board_count();

        //문제없이 받아옴
        $per_page = $config['per_page'] = 10;
        $config['num_links'] = 1;

        $config['page_query_string'] = true; //쿼리스트링 변환

        $config['query_string_segment'] = 'page';

        $this->pagination->initialize($config);

        $page = $this->input->get("page");

        if ($page <= 0) {
            $page = 1;
        }

        $offset = $page - 1;

        $pagination = $this->pagination->create_links();

        $authority = $this->session->userdata("authority");
        $result = $this->layout_model->main_list_pagi($per_page, $offset);

        $gongji = $this->layout_model->gongji_board();

        $this->layout_common->view(
            "/main/main_view",
            array(
                "result" => $result,
                "ct_num" => $ct_num,
                "all_gongji_board" => $gongji,
                "authority" => $authority,
                "pagination" => $pagination
            )
        );
    }


    function full_board_list_search()
    {
        $ct_num = 2;
        $day = $this->input->get("category_option_1");
        $search_sel = $this->input->get("category_option_2");
        $search_title = $this->input->get("board_footer_search");

        $config['base_url'] = base_url("/layout/full_board_list");
        $config['uri_segment'] = 4;
        $config['total_rows'] = $this->layout_model->main_board_search_count($day, $search_sel, $search_title);

        $per_page = $config['per_page'] = 10;
        $config['num_links'] = 1;

        $config['page_query_string'] = true; //쿼리스트링 변환

        $config['query_string_segment'] = 'page';

        $this->pagination->initialize($config);

        $page = $this->input->get("page");

        if ($page <= 0) {
            $page = 1;
        }

        $offset = $page - 1;

        $pagination = $this->pagination->create_links();

        $search_main_list = $this->layout_model->main_board_search($day, $search_sel, $search_title,$per_page,$offset);

        $authority = $this->session->userdata("authority");
        $gongji = $this->layout_model->gongji_board();

        $this->layout_common->view(
            "/main/main_view",
            array(
                "result" => $search_main_list,
                "ct_num" => $ct_num,
                "all_gongji_board" => $gongji,
                "authority" => $authority,
                "pagination" => $pagination
            )
        );
    }

    public function ck_login()
    {
        $logged_in = $this->session->userdata("id");

        header('Content-Type: application/json');
        echo json_encode(array('logged_in' => $logged_in));
    }
    //카테고리 추가
    public function category_insert()
    {
        $category_name = $this->input->post("category_name");

        $this->layout_model->category_insert($category_name);

        echo "<script>
        alert('추가되었습니다.');
        location.href='/layout';</script>";
    }
    //카테고리 유효성 검사
    function check_category()
    {
        $category_name = $this->input->post("category_name");

        $duplicate = $this->layout_model->ck_category($category_name);

        header('Content-Type: application/json');
        echo json_encode(array("duplicate" => $duplicate));
    }
    //카테고리 제거
    function del_cate()
    {
        $ct_num = $this->input->post("del_list");

        $this->layout_model->delete($ct_num);

        echo "<script>
        alert('제거되었습니다.');
        location.href='/layout';</script>";
        // redirect($this->agent->referrer()); //전페이지로 돌아가는 로직
    }



    // 회원정보 수정 가기전 아이디비밀번호 확인
    function ck_modify_member()
    {
        $id = $this->input->post("member_modify_confirm_id");
        $password = $this->input->post("member_modify_confirm_pw");
        $login_id = $this->session->userdata("id");

        if ($login_id == $id) {
            $ck_member = $this->layout_model->ck_modify_mem($id);
            if (password_verify($password, $ck_member->user_pw)) {
                // echo json_encode(array('valid'=>true));//성공했을때
                redirect("/member/member_modify");
            } else {
                echo "<script>
                alert('회원정보가 일치하지 않습니다.');
                location.href='/layout';</script>";
            }

        } else {
            // echo json_encode(array('valid'=> false));//실패했을때
            echo "<script>
            alert('일치하지 않습니다.');
            location.href='/layout';</script>";
        }
    }


    function board_search()
    {
        $ct_num = 2;

        $search_title = $this->input->get("hd_search");

        if ($search_title != "") {
            $config['base_url'] = base_url("/layout/board_search?hd_search=$search_title");
            $config['uri_segment'] = 4;
            $config['total_rows'] = $this->layout_model->header_search_count($search_title);

            echo $config['total_rows'];
            $per_page = $config['per_page'] = 10;
            $config['num_links'] = 1;

            $config['page_query_string'] = true; //쿼리스트링 변환

            $config['query_string_segment'] = 'page';

            $this->pagination->initialize($config);

            $page = $this->input->get("page");

            if ($page <= 0) {
                $page = 1;
            }

            $offset = $page - 1;

            $pagination = $this->pagination->create_links();

            $result = $this->layout_model->header_search_list($per_page, $offset, $search_title);


            $gongji = $this->layout_model->gongji_board();

            $this->layout_common->view(
                "/main/main_view",
                array(
                    "result" => $result,
                    "ct_num" => $ct_num,
                    "pagination" => $pagination,
                    "all_gongji_board" => $gongji
                )
            );
        } else {
            echo "<script>
                        alert('검색타이틀을 넣어주세요.');
                        location.href='/layout';</script>";

        }

    }
}

?>