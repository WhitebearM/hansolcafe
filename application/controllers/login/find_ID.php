<?
class find_ID extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->database();
        $this->load->model("/login/find_ID_model");
        $this->load->library("session");
        $this->load->helper(array('form','url'));
        $this->load->library('form_validation');

    }

    public function index(){
        $data['result'] = false;
        $this->load->view("/login/find_ID_view",$data);
    }

    public function find_ID(){
        $this->form_validation->set_rules('find_id_email', 'find_id_email', 'required');

        if($this->form_validation->run()== false){
            echo "<script>
            alert('이메일을 입력해주세요.');
            location.href='/login/find_ID';</script>";
        }else{
        $search_id = $this->input->post("find_id_email");

        $data['result'] = $this->find_ID_model->find_ID($search_id);

        if($data['result'] != ""){
            if($data['result']->user_email == $search_id){
                $this->load->view("login/find_ID_view",$data);
            }
        }else{
            echo "<script>
            alert('해당하는 이메일이 존재하지 않습니다.');
            location.href='/login/find_ID';</script>";
        }
        
    }
}
}