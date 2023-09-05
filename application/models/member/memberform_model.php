<?

class memberform_model extends CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function uidck($user_id){
        return $this->db->query("select user_id from member where user_id = '$user_id'")->result();
    }

    public function uinsert($id,$pw,$email,$name,$image_path){
        $this->db->query("insert into member(user_id,user_pw,user_email,user_name,image_path) values('$id','$pw','$email','$name','$image_path')");
    }

    public function emailck($email){
        return $this->db->query("select user_email from member where user_email = '$email'")->result();
    }
}
?>