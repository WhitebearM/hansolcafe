<?

class login extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model("/login/login_model");
        $this->load->library('session');
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
    }
    

    public function index(){
        $this->load->view("/login/login_view");
    }

    public function login_member(){
        $this->form_validation->set_rules('user_id', 'user_id', 'required');
        $this->form_validation->set_rules('user_pw', 'user_pw', 'required');

        if($this->form_validation->run()== false){
            echo "<script>
            alert('오류가 발생했습니다.');
            location.href='/login/login';</script>";
        }else{
        $id = $this->input->post("user_id");
        $pw = $this->input->post("user_pw");

        $result = $this->login_model->login($id);
        
        // 입력한 id 와 서버에 id가 일치할경우
        if(isset($result)){ 
            if($id == $result->user_id ){
                    // 입력한 pw 와 서버에서 가져와 복호화한 pw일치할경우
                    if (password_verify($pw, $result->user_pw) && isset($result)) {
                        // 세션에 id 추가
                        $this->session->set_userdata("id", $result->user_id);
                        $this->session->set_userdata("id_authority", $result->authority);
                        $this->session->set_userdata("authority", $result->authority);
                        //세션에 추가 되었을경우만 방문횟수 증가
                        if ($this->session->userdata('id')) {
                            $this->login_model->visit($id);
                        }
                        redirect("/layout");
                    } else {
                        echo "<script>
                    alert('아이디나 비밀번호가 틀렸습니다.');
                    location.href='/login/login';</script>";
                    }
            }else{
                echo "<script>
                alert('아이디나 비밀번호가 틀렸습니다.');
                location.href='/login/login';</script>";
            }
        }else{
            echo "<script>
                alert('아이디나 비밀번호가 틀렸습니다.');
                location.href='/login/login';</script>"; 
        }

    }
}
    public function logout_member(){
        unset($_SESSION['id']);
        unset($_SESSION['id_authority']);
        echo "<script>
        alert('로그아웃 되었습니다..');
        location.href='/layout';</script>";
    }
}