<?

class find_PW extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model("/login/find_PW_model");
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');
    }


    public function index(){
        $data['result'] = false;
        $this->load->view("/login/find_PW_view",$data);
    }

    public function find_PW(){
        $this->form_validation->set_rules('find_pw_id', 'find_pw_id', 'required');
        $this->form_validation->set_rules('find_pw_email', 'find_pw_email', 'required');

        if($this->form_validation->run()== false){
            echo "<script>
            alert('오류가 발생했습니다.');
            location.href='/login/find_PW';</script>";
        }else{
       $find_id = $this->input->post("find_pw_id");
       $find_email = $this->input->post("find_pw_email");

       $sql = $this->find_PW_model->find_PW($find_id,$find_email);

       if($sql){
        $data['result'] = true;
        $data['user_id'] = $find_id;
        $this->load->view("/login/find_PW_view",$data);
       }else{
            echo "<script>
            alert('존재하지않는 아이디거나 이메일입니다.');
            location.href='/login/find_PW';</script>";
       }
    }
    }
    public function update_PW(){
        $this->form_validation->set_rules('check_id', 'check_id', 'required');
        $this->form_validation->set_rules('change_pw', 'change_pw', 'required');

        if($this->form_validation->run()== false){
            echo "<script>
            alert('오류가 발생했습니다.');
            location.href='/login/find_PW';</script>";
        }else{ 
        $id = $this->input->post("check_id");
        $pw = $this->input->post("change_pw");
        
        $hashed_password = password_hash($pw,PASSWORD_DEFAULT);

        $this->find_PW_model->update_PW($id,$hashed_password);

        echo "<script>
        alert('변경되었습니다.');
        location.href='/login/login';</script>";
        }
    }
}