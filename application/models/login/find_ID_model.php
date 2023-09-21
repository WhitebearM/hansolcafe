<?
class find_ID_model extends CI_Model{
    function __construct(){
        $this->load->database();
    }

    function find_ID($find_email){
        $sql = $this->db->query("select * from member where user_email = '$find_email'")->row();

        return $sql;

    }
}