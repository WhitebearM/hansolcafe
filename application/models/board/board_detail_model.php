<?

class board_detail_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function category_info($category)
    {
        $result = $this->db->query("select * from category where category_num = '$category'")->result();

        foreach ($result as $row) {
            return $row;
        }
    }
    function category_all()
    {
        return $this->db->query("select * from category")->result();
    }

    function board_detail($board_num)
    {
        $result = $this->db->query("select * FROM board WHERE article_num = '$board_num'")->result();

        foreach ($result as $row) {
            return $row;
        }
    }

    function board_comments($board_num)
    {
        return $this->db->query("select COUNT(*) AS comments_count
                                    FROM comments
                                    WHERE article_num = '$board_num'")->result();

    }

    function board_hearts($board_num)
    {
        return $this->db->query("select COUNT(*) as heart_count
                                    FROM heart
                                    WHERE article_num = '$board_num'")->result();
    }

    function board_semi_list()
    {
        return $this->db->query("select * from board order by write_date desc limit 5")->result();

    }

    function delete($board_num)
    {
        $current_date = date('Y-m-d H:i:s');

        // $this->db->query("update board SET category_num = 0, board_status = 2, delete_date = '$current_date' WHERE article_num = $board_num AND board_status = 1");
        $current_date = date('Y-m-d H:i:s'); // 현재 날짜 및 시간

        $data = array(
            'category_num' => 0,
            'board_status' => 2,
            'delete_date' => $current_date
        );

        $this->db->where('article_num', $board_num);
        $this->db->where('board_status', 1);
        $this->db->update('board', $data);


    }

    function category_modify($category_num, $article_num)
    {
        $this->db->query("update board set category_num = '$category_num' where article_num = '$article_num'");
    }

    function change_main_status($article_num)
    {
        $this->db->query("update board
        SET main_status = CASE
            WHEN main_status = 1 THEN 2
            WHEN main_status = 2 THEN 1
            ELSE main_status
            END
            WHERE article_num = '$article_num'");
    }

    function write_comment($comment_content, $comment_user_id, $comment_article_num, $comment_parent_id)
    {
        $max_grp_query = $this->db->query("select MAX(grp) AS max_grp FROM comments WHERE parent_id = 0");
        $max_grp_result = $max_grp_query->row();
        $next_grp = ($max_grp_result->max_grp !== null) ? $max_grp_result->max_grp + 1 : 1;

        $data = array(
            'parent_id' => 0,
            'user_id' => $comment_user_id,
            'article_num' => $comment_article_num,
            'content' => $comment_content,
            'grp' => $next_grp,
            'depth' => 0, // Depth is always 0 for top-level comments
        );

        $this->db->insert('comments', $data);
    }
    private function get_highest_grp()
    {
        $this->db->select_max('grp');
        $query = $this->db->get('comments');
        $result = $query->row();
        return $result->grp;
    }


    function board_comment($board_num)
    {
        return $this->db->query("with RECURSIVE CommentHierarchy AS (
            SELECT
                comment_num,
                article_num,
                parent_id,
                user_id,
                grp,
                depth,
                content,
                write_date
            FROM
                comments
            WHERE
                parent_id = 0
                AND article_num = '$board_num'
                
            UNION ALL
            
            SELECT
                c.comment_num,
                c.article_num,
                c.parent_id,
                c.user_id,
                c.grp,
                c.depth,
                c.content,
                c.write_date
            FROM
                comments c
            JOIN
                CommentHierarchy ch ON c.parent_id = ch.comment_num
        )
        SELECT
            comment_num,
            article_num,
            parent_id,
            user_id,
            grp,
            depth,
            content,
            write_date
        FROM
            CommentHierarchy
        ORDER BY
            grp, depth, write_date;
        
        
        ")->result();

    }


    function get_board_file_info($article_num)
    {
        $sql = $this->db->get_where("fileupload", array("article_num" => $article_num));

        return $sql->row();
    }

    function comment_delete($comment_num)
    {
        $this->db->query("delete from comments where comment_num= '$comment_num'");
    }

    // function board_modify($id, $gongji, $dcsr, $category, $title, $content){
    //     $this->db->query("update board set ");
    // }

    function comment_update($comment_num, $content)
    {
        $this->db->query("update comments set content = '$content' where comment_num = '$comment_num'");
    }


    public function get_heart_count($article_num)
    {
        $query = $this->db->get_where('heart', array('article_num' => $article_num));
        return $query->num_rows();
    }

    public function toggle_like($article_num, $user_id)
    {
        $query = $this->db->get_where('heart', array('article_num' => $article_num, 'user_id' => $user_id));
        $is_liked = $query->num_rows() > 0;

        if ($is_liked) {
            $this->db->delete('heart', array('article_num' => $article_num, 'user_id' => $user_id));
        } else {
            $this->db->insert('heart', array('article_num' => $article_num, 'user_id' => $user_id));
        }

        return !$is_liked;
    }

    function board_heart_check($id, $board_num)
    {
        $sql = $this->db->get_where('heart', array('user_id' => $id, 'article_num' => $board_num))->result();

        if ($sql) {
            return true;
        } else {
            return false;
        }
    }

    function recomment_write($parent_id, $content, $comment_num, $id, $article_num)
    {
        $parent_comment = $this->db->get_where('comments', array('comment_num' => $comment_num))->row();

        if (!$parent_comment) {
            return false;
        }
        $data = array(
            'parent_id' => $parent_comment->comment_num,
            'article_num' => $parent_comment->article_num,
            'user_id' => $id,
            'grp' => $parent_comment->grp,
            'depth' => $parent_comment->depth + 1,
            'content' => $content
        );


        $this->db->insert('comments', $data);
    }


    function board_user_info($id){
        $user_info = $this->db->query("select * from member where user_id = '$id'")->row();

        return $user_info;
    }
}