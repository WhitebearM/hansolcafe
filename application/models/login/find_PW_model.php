<?

class find_PW_model extends CI_Model{

    function find_pw($find_id,$find_email){
        $sql = $this->db->query("select * from member where user_id='$find_id'and user_email='$find_email'")->result();

        foreach($sql as $row){
            return $row;
        }
    }
    function update_PW($id,$pw){
        $this->db->query("update member set user_pw = '$pw' where user_id = '$id'");
    }

}