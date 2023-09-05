<?

class member_activity extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library("layout_common");
        $this->load->library("session");
        $this->load->library('form_validation');
        $this->load->model("/member/member_activity_model");
    }

    function index()
    {
        $id = $this->session->userdata("id");

        $act_board = $this->member_activity_model->member_act_board($id);
        $act_comments = $this->member_activity_model->member_act_comments($id);
        $member_info = $this->member_activity_model->member_info($id);

        $this->layout_common->view(
            "/member/member_activity_view",
            array(
                "act_board" => $act_board,
                "act_comments" => $act_comments,
                "member" => $member_info
            )
        );
    }


    function selected_delete_board()
    {
        
        if (isset($_POST['member_activity_checkbox']) && is_array($_POST['member_activity_checkbox']) && count($_POST['member_activity_checkbox']) > 0) {
            
            $select_posts = $this->input->post("member_activity_checkbox");
            foreach ($select_posts as $post) {
                $this->member_activity_model->delete_board($post);
            }

            echo "<script>
            alert('해당게시물이 삭제되었습니다.');
            location.href='/member/member_activity';</script>";
        }else{
            echo "<script>
            alert('삭제할 게시물을 선택해주세요.');
            location.href='/member/member_activity';</script>"; 
        }
    }

    function selected_delete_comment(){
        if (isset($_POST['member_activity_checkbox']) && is_array($_POST['member_activity_checkbox']) && count($_POST['member_activity_checkbox']) > 0) {
            $select_comments = $this->input->post("member_activity_checkbox");

            foreach($select_comments as $comment){
                $this->member_activity_model->delete_comment($comment);
            }

            echo "<script>
            alert('해당 댓글이 삭제되었습니다.');
            location.href='/member/member_activity';</script>";

        }else{
            echo "<script>
            alert('삭제할 댓글을 선택해주세요.');
            location.href='/member/member_activity';</script>"; 
        }

    }

    function board_restore(){
        if (isset($_POST['member_activity_checkbox']) && is_array($_POST['member_activity_checkbox']) && count($_POST['member_activity_checkbox']) > 0) {
            $select_reboard = $this->input->post("member_activity_checkbox");

            foreach($select_reboard as $board){
                $this->member_activity_model->restore_board($board);
            }

            echo "<script>
            alert('해당 게시글이 복구되었습니다.');
            location.href='/member/member_activity';</script>";
            
        }else{
            echo "<script>
            alert('복구할 게시물을 선택해주세요.');
            location.href='/member/member_activity';</script>"; 
        }
    }
}