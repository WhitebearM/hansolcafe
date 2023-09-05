<?

class login_model extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function login($id){
        $sql = $this->db->get_where("member",array("user_id" => $id));

        foreach($sql->result() as $row){
            return $row;
        }
    }

    function visit($id){
        $this->db->query("update member set count = count + 1 where user_id = '$id'");
    }
}