<?
class member_delete extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library("form_validation");
        $this->load->library("session");
        $this->load->model("/member/member_delete_model");
    }


    function index()
    {
        $login_id = $this->session->userdata("id");

        if (isset($login_id)) {
            $this->load->view("/member/member_delete_view");
        } else {
            unset($_SESSION['id']);
            unset($_SESSION['id_authority']);
            echo "<script>
            alert('재로그인 해주세요!');
            location.href='/login/login';</script>";
        }
    }


    function member_del()
    {
        $this->form_validation->set_rules('delete_id', 'delete_id', 'required');
        $this->form_validation->set_rules('delete_pw', 'delete_pw', 'required');
        $this->form_validation->set_rules('delete_email', 'delete_email', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo "<script>
            alert('정보를 입력해주세요!');
            location.href='/member/member_delete';</script>";
        } else {
            $user_id = $this->input->post("delete_id");
            $user_pw = $this->input->post("delete_pw");
            $user_email = $this->input->post("delete_email");

            $password_eq = $this->member_delete_model->member_pw_eq($user_id);

            if (isset($password_eq->user_pw)) {
                if (password_verify($user_pw, $password_eq->user_pw)) {
                    $result = $this->member_delete_model->member_del($user_id, $user_email);

                    if ($result == 1) {
                        unset($_SESSION['id']);
                        unset($_SESSION['id_authority']);
                        echo "<script>
                    alert('회원이 탈퇴되었습니다.');
                    location.href='/layout';</script>";

                    }else{
                        echo "<script>
                    alert('입력하신 정보가 틀렸습니다.');
                    location.href='/member/member_delete';</script>";    
                    }
                } else {
                        echo "<script>
                    alert('입력하신 정보가 틀렸습니다.');
                    location.href='/member/member_delete';</script>";
                }

            } else {
                echo "<script>
                alert('입력하신 정보가 틀렸습니다.');
                location.href='/member/member_delete';</script>";

            }
        }
    }

}