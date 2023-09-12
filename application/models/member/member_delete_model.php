<?

class member_delete_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function member_del($user_id,$user_email){
        $this->db->where('user_id', $user_id);
        $this->db->where('user_email', $user_email);

        $this->db->delete('member');

        return $this->db->affected_rows() > 0;
    }

    function member_pw_eq($user_id){
        $result = $this->db->query("select * from member where user_id = '$user_id'")->result();

        foreach($result as $row){
            return $row;
        }
    }
}