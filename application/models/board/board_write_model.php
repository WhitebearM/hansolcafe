<?

class board_write_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function list_category()
    {
        $sql = $this->db->query("select * from category");
        $list = array();

        foreach ($sql->result() as $result) {
            $category = array(
                'category_num' => $result->category_num,
                'category_name' => $result->category_name
            );
            $list[] = $category;
        }

        return $list;
    }

    function create_board($id, $gongji, $dcsr, $category, $title, $content)
    {
        // $this->db->query("insert into board(user_id,main_status,disclosure,category_num,title,content) values('$id','$gongji','$dcsr','$category','$title','$content')");

        // 그룹 ID 설정
        $max_grp = $this->db->select_max('grp')->get('board')->row()->grp;
        $grp = $max_grp + 1;

        // 같은 그룹 내에서의 순서 설정
        $max_seq = $this->db->where('grp', $grp)->select_max('seq')->get('board')->row()->seq;
        $seq = $max_seq + 1;

        $data = array(
            'user_id' => $id,
            'main_status' => $gongji,
            'parent_id' => 0,
            'disclosure' => $dcsr,
            'category_num' => $category,
            'title' => $title,
            'content' => $content,
            'grp' => $grp,
            'seq' => 1,
            'depth' => 0
        );

        $this->db->insert('board', $data);

        $inserted_board_id = $this->db->insert_id();

        $query = $this->db->query("select * from board where article_num = '$inserted_board_id'")->result();

        foreach ($query as $row) {
            $query = $row;
        }

        return array(
            'article_num' => $inserted_board_id,
            'category_num' => $query->category_num
        );
    }


    function article_num($id)
    {
        $sql = $this->db->query("select article_num from board where user_id = '$id'");

        foreach ($sql->result() as $row) {
            return $row;
        }
    }

    function fileupload($article_num, $file_path, $original_file_name, $save_file_name)
    {
        $this->db->query("insert into fileupload(article_num,file_path,file_name,file_save_name) values('$article_num','$file_path','$original_file_name','$save_file_name')");
    }

    function fileupload_update($board_num, $file_path, $original_file_name, $save_file_name)
    {
        $this->db->query("insert into fileupload (article_num,file_save_name,file_name,file_path) values('$board_num','$save_file_name','$original_file_name','$file_path')");
    }

    function modify_sel_board($article_num)
    {
        $sql = $this->db->query("select * from board where article_num = '$article_num'")->result();

        foreach ($sql as $row) {
            return $row;
        }
    }

    function select_file_board($article_num){
        $this->db->query("select file_num from fileupload where article_num = '$article_num'");
    }

    function modify_delete_file($file_num){
        $this->db->query("delete from fileupload where file_num = '$file_num'");
    }

    function modify_file($article_num){
        $sql = $this->db->query("select * from fileupload where article_num = '$article_num'")->result();

        return $sql;
    }

    function board_modify($board_num, $gongji, $dcsr, $category, $title, $content)
    {
        // $this->db->query("delete from fileupload where article_num = '$board_num'");
        $this->db->query("update board set main_status = '$gongji',disclosure = '$dcsr',category_num = '$category',title = '$title',content= '$content' where article_num = '$board_num'");

        $sql = $this->db->query("select * from board where article_num = '$board_num'")->result();

        foreach ($sql as $row) {
            return $row;
        }
    }

    // 답글 다는부분
    function reply_write_board($id, $gongji, $dcsr, $category, $title, $content, $parent_num)
    {
        $parent_post = $this->db->get_where('board', array("article_num" => $parent_num))->row();

        if (!$parent_post) {
            return false;
        }

        $this->db->where('grp', $parent_post->grp)
            ->where('seq >=', $parent_post->seq)
            ->where('article_num !=', $parent_post->article_num) // 부모 게시글 제외
            ->set('seq', 'seq+1', FALSE)
            ->update('board');

        // 새로운 게시글 데이터 생성
        $data = array(
            'user_id' => $id,
            'category_num' => $category,
            'title' => $title,
            'content' => $content,
            'parent_id' => $parent_num,
            'grp' => $parent_post->grp,
            'seq' => $parent_post->seq + 1,
            'depth' => $parent_post->depth + 1
        );

        $this->db->insert('board', $data);
        $insert_id = $this->db->insert_id();

        $insert_board_info = $this->db->get_where('board', array('article_num' => $insert_id))->row();

        $data = array(
            'article_num' => $insert_board_info->article_num,
            'category_num' => $insert_board_info->category_num
        );

        return $data;
    }

    function parent_board_info($board_num){
        $this->db->select('*');
        $this->db->from('board');
        $this->db->where('article_num' , $board_num);
        $query = $this->db->get();

        return $query->row();
    }
}