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
            'disclosure' => $dcsr,
            'category_num' => $category,
            'title' => $title,
            'content' => $content,
            'grp' => $grp,
            'seq' => 0,
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
        $this->db->query("update fileupload set file_save_name = '$save_file_name', file_name = '$original_file_name', file_path='$file_path' where article_num = $board_num");
    }

    function modify_sel_board($article_num)
    {
        $sql = $this->db->query("select * from board where article_num = '$article_num'")->result();

        foreach ($sql as $row) {
            return $row;
        }
    }

    function board_modify($board_num, $gongji, $dcsr, $category, $title, $content)
    {
        $this->db->query("update board set main_status = '$gongji',disclosure = '$dcsr',category_num = '$category',title = '$title',content= '$content' where article_num = '$board_num'");

        $sql = $this->db->query("select * from board where article_num = '$board_num'")->result();

        foreach ($sql as $row) {
            return $row;
        }
    }

    function reply_write_board($id, $gongji, $dcsr, $category, $title, $content, $parent_num)
    {
        // $parent_data = $this->db->get_where('board', array("article_num" => $parent_num))->row();

        // // 부모 글의 마지막 답글 seq 값을 가져옵니다.
        // $max_seq = $this->db
        //     ->select_max('seq')
        //     ->where('grp', $parent_data->grp)
        //     ->get('board')
        //     ->row()
        //     ->seq;

        // $new_seq = $parent_data->seq + 1; // 부모 글의 seq + 1

        // // 중복된 seq 값이 이미 존재하는 경우
        // if ($new_seq <= $max_seq) {
        //     // 중복을 피하기 위해 최대 seq 값 + 1로 조정
        //     $new_seq = $max_seq + 1;
        // }

        // $data = array(
        //     'user_id' => $id,
        //     'main_status' => $gongji,
        //     'disclosure' => $dcsr,
        //     'category_num' => $category,
        //     'title' => $title,
        //     'content' => $content,
        //     'parent_id' => $parent_num,
        //     'grp' => $parent_data->grp,
        //     'seq' => $new_seq,
        //     'depth' => $parent_data->depth + 1
        // );

        // $this->db->insert('board', $data);
        // $insert_id = $this->db->insert_id();

        // $insert_board_info = $this->db->get_where('board', array('article_num' => $insert_id))->row();

        // $data = array(
        //     'article_num' => $insert_board_info->article_num,
        //     'category_num' => $insert_board_info->category_num
        // );

        // return $data;
        $parent_data = $this->db->get_where('board', array("article_num" => $parent_num))->row();

        $new_depth = $parent_data->depth + 1; // 부모 답글의 깊이 + 1
        
        // 같은 grp 내에서 중복된 seq 값을 확인하고 조정
        $existing_seq = $this->db
            ->where('grp', $parent_data->grp)
            ->where('depth', $new_depth)
            ->get('board')
            ->row();
        
        if ($existing_seq) {
            // 같은 grp 내에서 중복된 seq 값이 이미 존재하는 경우
            // 부모 답글과의 seq 값을 조정
            $parent_seq = $parent_data->seq;
            $existing_seq->seq = $parent_seq + 1;
        
            // 해당 seq 값보다 큰 다른 답글들의 seq 값을 증가
            $this->db->where('grp', $parent_data->grp)
                     ->where('depth', $new_depth)
                     ->where('seq >', $parent_seq)
                     ->set('seq', 'seq+1', false)
                     ->update('board');
        } else {
            // 중복된 seq 값이 없는 경우, 부모 답글의 seq 값을 사용
            $parent_seq = $parent_data->seq;
        }

        $data = array(
            'user_id' => $id,
            'main_status' => $gongji,
            'disclosure' => $dcsr,
            'category_num' => $category,
            'title' => $title,
            'content' => $content,
            'parent_id' => $parent_num,
            'grp' => $parent_data->grp,
            'seq' => $parent_seq + 1,
            // 부모 답글의 seq 값에 1을 더하여 설정
            'depth' => $new_depth
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

}