<?

class layout_common
{
    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model("layout_model");
        $this->CI->load->library("session");

    }

    public function view($view, $view_data = null)
    {
            $id = $this->CI->session->userdata("id");

            $admin = $this->CI->layout_model->selAdmin(); //어드민정보
            $member = $this->CI->layout_model->selmember($id); //로그인한 유저정보
            $result_count = $this->CI->layout_model->count(); //카페 회원수
            $member_board_count = $this->CI->layout_model->board_count($id); //해당 로그인한 유저가 쓴 게시글수
            $member_comment_count = $this->CI->layout_model->comment_count($id); //해당 로그인한 유저가 쓴 댓글수
            $category_list = $this->CI->layout_model->category_list();
            $date_desc_board = $this->CI->layout_model->date_desc_board();

            $layout_view_data = array(
                "content" => $this->CI->load->view($view, $view_data, true),
                "id" => $this->CI->session->userdata("id"),
                "authority" => $this->CI->session->userdata("authority"),
                "result" => $admin,
                "member" => $member,
                "count" => $result_count,
                "board_count" => $member_board_count,
                "comment_count" => $member_comment_count,
                "category_list" => $category_list,
                "date_board" =>$date_desc_board,
            );
            $this->CI->load->view("layout_view", $layout_view_data);
        }

}