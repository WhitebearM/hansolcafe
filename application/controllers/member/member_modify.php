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
            alert('잘못된 방법으로 접근을 시도했습니다.');
            location.href='/member/member_modify';</script>";

        } else {

            $user_id = $this->session->userdata("id");
            $nickname = $this->input->post("user_nickname");
            $nickname = html_escape($nickname);
            if(!empty($_POST['user_pw'])){
                $pw = $this->input->post("user_pw");
                $pw = html_escape($pw);
                $password_hashed = password_hash($pw, PASSWORD_DEFAULT);
            }
            $email = $this->input->post("user_email");
            $email = html_escape($email);

            if($email != ""){
                $result = $this->member_modify_model->member_email_ck($email);
                if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    if($result != ""){
                        echo "<script>
                        alert('중복된 이메일 입니다.');
                        location.href='/member/member_modify';</script>";
                    }else{
                        if($_FILES['profilePic']['name'] != ""){
                            //이미지 업로드부분
                            $config['upload_path'] = 'C:\cloneproject\ci\uploadimg'; //경로
                            $config['allowed_types'] = 'gif|jpg|png'; //허용할 타입
                            $config['max_size'] = 250;
                
                            $this->load->library('upload', $config);
                
                            if (!$this->upload->do_upload('profilePic')) { //업로드실패
                                $data = $this->upload->data();
                                $error = array('error' => $this->upload->display_errors());
                            } else {
                                $data = $this->upload->data();
                                $image_path = $data['full_path']; //풀네임경로
                                $wep_root = "C:/cloneproject/ci";
                                $relative_image_path = str_replace($wep_root, "", $image_path);
                                if(isset($pw)){
                                    $this->member_modify_model->member_modify_update($user_id, $nickname, $password_hashed, $email,$relative_image_path);
                                }else{
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
                }else{
                    echo "<script>
                    alert('메일 형식이 잘못되었습니다.');
                    location.href='/member/member_modify';</script>";
                }
            }else{
                echo "<script>
                alert('이메일을 빈칸으로 수정할수 없습니다.');
                location.href='/member/member_modify';</script>";
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

            $isDuplicate = $this->member_modify_model->member_email_ck($user_email);

            if($isDuplicate != ""){
                $isDuplicate = true;
            }else{
                $isDuplicate = false;
            }
            
            echo json_encode(array('isDuplicate' => $isDuplicate));
        }
    }
}