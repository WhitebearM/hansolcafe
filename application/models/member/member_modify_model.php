<?

class member_modify_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function member_info($id){
        $user_id = $id['id'];
        $sql = $this->db->query("select * from member where user_id = '$user_id'")->result_array();

        foreach($sql as $row){
            return $row;
        }
    }

    function member_modify_update($user_id,$nickname,$pw,$email,$relative_image_path){
            $this->db->query("update member set user_nickname = '$nickname', user_pw = '$pw' , user_email = '$email',image_path = '$relative_image_path' where user_id= '$user_id'");
    
    }

    function no_image_member_modify_update($user_id, $nickname, $password_hashed, $email){
            $this->db->query("update member set user_nickname = '$nickname', user_pw = '$password_hashed' , user_email = '$email' where user_id= '$user_id'");

    }
}