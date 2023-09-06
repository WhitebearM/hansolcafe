<?

class board_list extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("/board/board_list_model");
        $this->load->library('layout_common');
        $this->load->library('user_agent');
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->database();
    }

    function index()
    {
        $division = 1;

        $category_name = $this->input->get("name");
        $category_num = $this->input->get("num");
        $select_val = $this->input->get("selected_page");


        $config['base_url'] = base_url("/board/board_list?name=$category_name&num=$category_num&selected_page=$select_val");
        $config['uri_segment'] = 4;

        $config['total_rows'] = $this->board_list_model->board_count($category_num);
        if ($select_val == null) {
            $per_page = $config['per_page'] = 5;
        } else {
            $per_page = $config['per_page'] = $select_val;
        }

        $config['num_links'] = 1;

        $config['page_query_string'] = true; //쿼리스트링 변환

        $config['query_string_segment'] = 'page';

        $this->pagination->initialize($config);

        $page = $this->input->get("page");
        if ($page <= 0) {
            $page = 1;
        }

        $offset = $page - 1;
        $pagenation = $this->pagination->create_links();

        if ($category_name != "미분류게시판" && $category_num != 0) {

            $id = $this->session->userdata("id");
            $authority = $this->session->userdata("authority");

            //모든 게시글
            $all_board_list = $this->board_list_model->all_board();
            //해당 카테고리에 게시글
            // $board_list = $this->board_list_model->board_list($category_num);
            $board_list = $this->board_list_model->board_list($category_num, $per_page, $offset);
            $category_list = $this->board_list_model->category_list();

            $this->layout_common->view(
                "/board/board_list_view",
                array(
                    "category_num" => $category_num,
                    "category_name" => $category_name,
                    "result" => $board_list,
                    "id" => $id,
                    "per_page" => $per_page,
                    "pagenation" => $pagenation,
                    "authority" => $authority,
                    "all_board" => $all_board_list,
                    "category_list" => $category_list,
                    "division" => $division
                )
            );
        } else {
            echo "<script>
            alert('접근 할 수 없는 환경입니다.');
            location.href='/layout';</script>";
        }
    }
    function pagination()
    {
        $division = 1;

        $category_name = $this->input->get("name");
        $category_num = $this->input->get("num");
        $select_val = $this->input->get("selected_page");

        if ($select_val === null) {
            // 만약 선택값이 없다면 세션에서 값을 읽어옴
            $select_val = $this->session->userdata('select_val');
        } else {
            // 선택값이 있다면 세션에 저장
            $this->session->set_userdata('select_val', $select_val);
        }

        $config['base_url'] = base_url("/board/board_list/pagination?name=$category_name&num=$category_num&selected_page=$select_val");
        $config['uri_segment'] = 4;

        $config['total_rows'] = $this->board_list_model->board_count($category_num);
        if ($select_val == null) {
            $per_page = $config['per_page'] = 5;
        } else {
            $per_page = $config['per_page'] = $select_val;
        }

        $config['num_links'] = 1;

        $config['page_query_string'] = true; //쿼리스트링 변환

        $config['query_string_segment'] = 'page';

        $this->pagination->initialize($config);

        $page = $this->input->get("page");
        if ($page <= 0) {
            $page = 1;
        }

        $offset = $page - 1;
        $pagenation = $this->pagination->create_links();

        if ($category_name != "미분류게시판" && $category_num != 0) {

            $id = $this->session->userdata("id");
            $authority = $this->session->userdata("authority");

            //모든 게시글
            //해당 카테고리에 게시글
            $all_board_list = $this->board_list_model->all_board();
            // $board_list = $this->board_list_model->board_list($category_num);
            $board_list = $this->board_list_model->board_list($category_num, $per_page, $offset);
            $category_list = $this->board_list_model->category_list();

            $this->layout_common->view(
                "/board/board_list_view",
                array(
                    "category_num" => $category_num,
                    "category_name" => $category_name,
                    "result" => $board_list,
                    "id" => $id,
                    "per_page" => $per_page,
                    "pagenation" => $pagenation,
                    "authority" => $authority,
                    "all_board" => $all_board_list,
                    "category_list" => $category_list,
                    "division" => $division
                )
            );
        } else {
            echo "<script>
            alert('접근 할 수 없는 환경입니다.');
            location.href='/layout';</script>";
        }
    }

    function sel_delete_board()
    {
        $post_num = $this->input->post("exception_article_num");
        $post_name = $this->input->post("exception_article_name");
        $category_num = $this->input->post("exception_category_num");

        if (isset($_POST['selected_board']) && is_array($_POST['selected_board']) && count($_POST['selected_board']) > 0) {
            $selected_board = $this->input->post("selected_board");

            foreach ($selected_board as $board) {
                $this->board_list_model->select_board_delete($board,$category_num);
            }

            echo "<script>
            alert('삭제되었습니다.');
            location.href='/board/board_list?name=$post_name&num=$post_num';</script>";
        } else {
            echo "<script>
            alert('게시물을 선택해 주세요.');
            location.href='/board/board_list?name=$post_name&num=$post_num';</script>";
        }
    }

    function board_category_move()
    {
        $this->form_validation->set_rules('sel_category', 'sel_category', 'required');
        $this->form_validation->set_rules('selected_article', 'selected_article', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo "<script>
            alert('선택해주세요.');</script>";
            redirect($this->agent->referrer());
        } else {
            $select_category = $this->input->post("sel_category");
            $select_board = $this->input->post("selected_article");

            $select_article_num = json_decode($select_board);

            var_dump($select_article_num);

            $gtr_name = $this->board_list_model->get_category_name($select_category);
            foreach ($select_article_num as $sel_list) {
                $this->board_list_model->select_board_move($sel_list, $select_category);
            }

            // echo "<script>
            // alert('게시물이 이동되었습니다.');
            // location.href='/board/board_list?name=$gtr_name->category_name&num=$gtr_name->category_num';</script>";
        }
    }

    function footer_search()
    {
        
        $division = 2;

        $category_name = $this->input->get("footer_search_categoryName");
        $category_num = $this->input->get("footer_search_categoryNum");


        $search_option1 = $this->input->get("category_option_1");
        $search_option2 = $this->input->get("category_option_2");

        $search_title3 = $this->input->get("board_footer_search");


        $config['base_url'] = base_url("/board/board_list/footer_search?category_option_1=$search_option1&category_option_2=$search_option2&footer_search_categoryNum=$category_num&footer_search_categoryName=$category_name&board_footer_search=$search_title3");
        $config['uri_segment'] = 4;

        $config['total_rows'] = $this->board_list_model->footer_search_count($category_num, $search_option1, $search_option2, $search_title3);
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
        $pagenation = $this->pagination->create_links();

        if ($category_name != "미분류게시판" && $category_num != 0) {
            $result = $this->board_list_model->footer_search($category_num, $search_option1, $search_option2, $search_title3,$per_page,$offset);
            $id = $this->session->userdata("id");
            $authority = $this->session->userdata("authority");

            $all_board_list = $this->board_list_model->all_board();
            $category_list = $this->board_list_model->category_list();


            $this->layout_common->view(
                "/board/board_list_view",
                array(
                    "category_num" => $category_num,
                    "category_name" => $category_name,
                    "result" => $result,
                    "id" => $id,
                    "authority" => $authority,
                    "per_page" => $per_page,
                    "pagenation" => $pagenation,
                    "all_board" => $all_board_list,
                    "category_list" => $category_list,
                    "division" => $division,
                    "option1" => $search_option1,
                    "option2" => $search_option2,
                    "option3" => $search_title3
                )
            );
        }
    }
}