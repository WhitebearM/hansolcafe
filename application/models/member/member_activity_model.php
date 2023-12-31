<?


class member_activity_model extends CI_Model{


    function member_info($id){
        $sql = $this->db->query("select 
        m.*, 
        COUNT(b.user_id) AS board_num
    FROM 
        member m
    LEFT JOIN 
        board b ON m.user_id = b.user_id
    WHERE 
        m.user_id = '$id'
    GROUP BY 
        m.user_id;
    ")->result();

        foreach($sql as $row){
            return $row;
        }
    }

    function member_act_board($id){
        return $this->db->query("select 
        board.*,
        COALESCE(comment_counts.comment_count, 0) AS comment_count,
        COALESCE(heart_counts.heart_count, 0) AS heart_count,
        (
            SELECT file_path
            FROM fileupload
            WHERE fileupload.article_num = board.article_num
            LIMIT 1
        ) AS file_path
    FROM 
        board
    LEFT JOIN (
        SELECT article_num, COUNT(*) AS comment_count
        FROM comments
        WHERE user_id = '$id'
        GROUP BY article_num
    ) AS comment_counts ON board.article_num = comment_counts.article_num
    LEFT JOIN (
        SELECT article_num, COUNT(*) AS heart_count
        FROM heart
        WHERE user_id = '$id'
        GROUP BY article_num
    ) AS heart_counts ON board.article_num = heart_counts.article_num
    WHERE 
        board.user_id = '$id';        
    ")->result();
    }
    // 멤버활동내역 게시물 파일 중복만큼 출력되는 쿼리문
    /* select distinct 
        board.*,
        COALESCE(comment_counts.comment_count, 0) AS comment_count,
        COALESCE(heart_counts.heart_count, 0) AS heart_count,
        fileupload.file_path
    FROM 
        board
    LEFT JOIN (
        SELECT article_num, COUNT(*) AS comment_count
        FROM comments
        WHERE user_id = '$id'
        GROUP BY article_num
    ) AS comment_counts ON board.article_num = comment_counts.article_num
    LEFT JOIN (
        SELECT article_num, COUNT(*) AS heart_count
        FROM heart
        WHERE user_id = '$id'
        GROUP BY article_num
    ) AS heart_counts ON board.article_num = heart_counts.article_num
    LEFT JOIN (
        SELECT article_num, file_path
        FROM fileupload
    ) AS fileupload ON board.article_num = fileupload.article_num
    WHERE 
        board.user_id = '$id';    */

    function member_act_comments($id){
        return $this->db->query("select 
        c.*,
        b.title AS board_title,
        b.category_num AS board_category_num
    FROM 
        comments c
    JOIN 
        board b ON c.article_num = b.article_num
    WHERE 
        c.user_id = '$id';")->result();
    }

    function delete_board($article_num){
        $current_date = date('Y-m-d H:i:s');
        $this->db->query("update board set board_status = 2, delete_date = '$current_date',del_category = category_num where article_num = '$article_num'");
    }

    function delete_comment($comment_num){
        $this->db->query("delete from comments where comment_num = '$comment_num'");
    }

    function restore_board($article_num){
        $this->db->query("update board set board_status = 1,category_num = del_category, del_category = 0 where article_num = '$article_num'");
    }
}