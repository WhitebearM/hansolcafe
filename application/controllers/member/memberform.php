<?

class memberform extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('/member/memberform_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }


    function index()
    {
        $this->load->view("/member/memberform_view");
    }

    //아이디중복확인
    function idck()
    {
        $user_id = $this->input->post("userid");
        $result = $this->memberform_model->uidck($user_id);

        if ($result) {
            echo json_encode(['message' => 'duplicate']);
        } else {
            echo json_encode(['message' => 'available']);
        }
    }

    //회원가입
    function create()
    {
        $this->form_validation->set_rules('user_id', 'user_id', 'required');
        $this->form_validation->set_rules('user_pw', 'user_pw', 'required');
        $this->form_validation->set_rules('user_email', 'user_email Confirmation', 'required');
        $this->form_validation->set_rules('user_name', 'user_name', 'required');
        if ($this->form_validation->run() == false) {
            echo "<script>
            alert('오류가 발생하였습니다.');
            location.href='/member/memberform';</script>";
        } else {
            $id = $this->input->post("user_id");
            $pw = $this->input->post("user_pw");
            $email = $this->input->post("user_email");
            $name = $this->input->post("user_name");

            //비밀번호 hash로 암호화
            $hashed_password = password_hash($pw, PASSWORD_DEFAULT);

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
                $relative_image_path = str_replace($wep_root,"",$image_path);
                $this->memberform_model->uinsert($id, $hashed_password, $email, $name, $relative_image_path);

                echo "<script>
            alert('회원가입이 정상적으로 완료되었습니다.');
            location.href='/login/login';</script>";
            }

        }
    }
    // 이메일중복확인
    function emailck()
    {
        $email = $this->input->post("user_email");
        $result = $this->memberform_model->emailck($email);

        if ($result) {
            echo json_encode(['message' => 'duplicate']);
        } else {
            echo json_encode(['message' => 'avilable']);
        }
    }
}
?>