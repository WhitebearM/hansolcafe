<?
class member_modify extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
        $this->load->model("/member/member_modify_model");
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    public function index()
    {
        $id = $this->session->get_userdata('id');
        $result = $this->member_modify_model->member_info($id);

        $this->load->view("/member/member_modify_view", array("info" => $result));
    }

    public function member_update()
    {
        $this->form_validation->set_rules('user_id', 'user_id', 'required');
        $this->form_validation->set_rules('user_nickname', 'user_nickname', 'required');
        $this->form_validation->set_rules('user_pw', 'Password Confirmation', 'required');
        $this->form_validation->set_rules('user_email', 'Email', 'required');

        if ($this->form_validation->run() == false) {
            echo "<script>
            alert('오류가 발생하였습니다.');
            location.href='/member/member_modify';</script>";

        } else {

            $user_id = $this->input->post("user_id");
            $nickname = $this->input->post("user_nickname");
            $pw = $this->input->post("user_pw");
            $email = $this->input->post("user_email");

            $password_hashed = password_hash($pw, PASSWORD_DEFAULT);

            if($_FILES['profilePic']['name'] != ""){
                //이미지 업로드부분
                $config['upload_path'] = 'C:\cloneproject\ci\uploadimg'; //경로
                $config['allowed_types'] = 'gif|jpg|png'; //허용할 타입
                $config['max_size'] = 250;
    
                $this->load->library('upload', $config);
    
                if (!$this->upload->do_upload('profilePic')) { //업로드실패
                    $data = $this->upload->data();
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);
                } else {
                    $data = $this->upload->data();
                    $image_path = $data['full_path']; //풀네임경로
                    $wep_root = "C:/cloneproject/ci";
                    $relative_image_path = str_replace($wep_root, "", $image_path);
                    $this->member_modify_model->member_modify_update($user_id, $nickname, $password_hashed, $email,$relative_image_path);
    
                    echo "<script>
                    alert('정보가 수정되었습니다.');
                    location.href='/layout';</script>";
                }
            }else{
                $this->member_modify_model->no_image_member_modify_update($user_id, $nickname, $password_hashed, $email);

                echo "<script>
                alert('정보가 수정되었습니다.');
                location.href='/layout';</script>";
            }
        }
    }
}