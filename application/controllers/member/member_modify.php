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

        $this->load->view("/member/member_modify_view", array("info" => $result,
                                                              "id" => $id));
    }

    public function member_update()
    {
        $this->form_validation->set_rules('user_id', 'user_id', 'required');
        $this->form_validation->set_rules('user_nickname', 'user_nickname', 'required');
        $this->form_validation->set_rules('user_email', 'Email', 'required');

        if ($this->form_validation->run() == false) {
            echo "<script>
            alert('오류가 발생하였습니다.');
            location.href='/member/member_modify';</script>";

        } else {

            $user_id = $this->input->post("user_id");
            $nickname = $this->input->post("user_nickname");
            $nickname = html_escape($nickname);
            if(!empty($_POST['user_pw'])){
                $pw = $this->input->post("user_pw");
                $pw = html_escape($pw);
                $password_hashed = password_hash($pw, PASSWORD_DEFAULT);
            }
            $email = $this->input->post("user_email");
            $email = html_escape($email);


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
                    if(isset($pw)){
                        echo 123;
                        $this->member_modify_model->member_modify_update($user_id, $nickname, $password_hashed, $email,$relative_image_path);
                    }else{
                        echo 456;
                        $this->member_modify_model->member_modify_update_empty_pw($user_id, $nickname, $email,$relative_image_path);
                    }
    
                     echo "<script>
                    alert('정보가 수정되었습니다.');
                    location.href='/layout';</script>";
                }
            }else{
                if(isset($pw)){
                    $this->member_modify_model->member_modify_update_empty_img($user_id, $nickname, $password_hashed, $email);
                }else{
                    $this->member_modify_model->member_modify_update_empty_img_pw($user_id, $nickname, $email);
                }
                echo "<script>
                alert('정보가 수정되었습니다.');
                location.href='/layout';</script>";
            }
        }
    }

    function check_Email(){
        $this->form_validation->set_rules('user_email', 'user_email', 'required');

        if ($this->form_validation->run() == false){            
            $id = $this->session->get_userdata('id');
            $result = $this->member_modify_model->member_info($id);
    
            echo "<script>
            alert('내용을 입력해주세요');</script>";

            $this->load->view("/member/member_modify_view", array("info" => $result,
                                                                  "id" => $id));
        }else{
            $user_email = $this->input->post("user_email");

            $isDuplicate = $this->member_modify_model->ck_email($user_email);
            
            echo json_encode(array('isDuplicate' => $isDuplicate));
        }
    }
}