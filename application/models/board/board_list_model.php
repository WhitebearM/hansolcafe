<?

class board_list_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function board_count($category_num)
    {
        $this->db->from("board");
        $this->db->where("category_num", $category_num);
        $this->db->where("board_status", 1);

        return $this->db->count_all_results();
    }

    public function board_list($category_num, $limit, $offset)
    {

        $sql = "with RECURSIVE PostHierarchy AS (
            SELECT
                article_num,
                category_num,
                title,
                parent_id,
                grp,
                seq,
                depth,
                b.board_status,
                b.write_date AS post_write_date,
                m.user_nickname
            FROM
                board b
            JOIN
                member m ON b.user_id = m.user_id
            WHERE
                parent_id = 0
                AND (board_status = 1 OR board_status = 2)
            UNION ALL
            SELECT
                b.article_num,
                b.category_num,
                b.title,
                b.parent_id,
                b.grp,
                b.seq,
                b.depth,
                b.board_status,
                ph.post_write_date,
                m.user_nickname
            FROM
                board b
            JOIN
                PostHierarchy ph ON b.parent_id = ph.article_num
            JOIN
                member m ON b.user_id = m.user_id
        )
        SELECT 
            ph.article_num,
            ph.category_num,
            ph.title,
            ph.parent_id,
            ph.grp,
            ph.seq,
            ph.depth,
            ph.board_status,
            (SELECT COUNT(*) FROM board WHERE parent_id = ph.article_num) AS child_count,
            COALESCE(comment_counts.comment_count, 0) AS comment_count,
            COALESCE(heart_counts.heart_count, 0) AS heart_count,
            COALESCE(GROUP_CONCAT(fu.file_path SEPARATOR ', '), '') AS file_path,
            COALESCE(GROUP_CONCAT(fu.file_name SEPARATOR ', '), '') AS file_name,
            b.content,
            b.user_id,
            ph.post_write_date AS write_date,
            CASE WHEN (SELECT board_status FROM board WHERE article_num = ph.parent_id) = 2 THEN 0 ELSE 1 END AS parent_valid,
            ph.user_nickname
        FROM PostHierarchy ph
        LEFT JOIN (
            SELECT article_num, COUNT(*) AS comment_count
            FROM comments
            GROUP BY article_num
        ) AS comment_counts ON ph.article_num = comment_counts.article_num
        LEFT JOIN (
            SELECT article_num, COUNT(*) AS heart_count
            FROM heart
            GROUP BY article_num
        ) AS heart_counts ON ph.article_num = heart_counts.article_num
        LEFT JOIN fileupload fu ON ph.article_num = fu.article_num
        LEFT JOIN board b ON ph.article_num = b.article_num
        WHERE
            ph.category_num = '$category_num'
        GROUP BY ph.article_num
        ORDER BY
            ph.post_write_date DESC, 
            ph.grp, ph.seq
        LIMIT $offset, $limit;";

        $query = $this->db->query($sql);
        return $query->result();



    }


    //공지사항
    function all_board()
    {
        return $this->db->query("select 
        board.*,
        COALESCE(comment_counts.comment_count, 0) AS comment_count,
        COALESCE(heart_counts.heart_count, 0) AS heart_count,
        fileupload.file_path,
        m.user_nickname
        FROM board
        LEFT JOIN (
        SELECT article_num, COUNT(*) AS comment_count
        FROM comments
        GROUP BY article_num
        ) AS comment_counts ON board.article_num = comment_counts.article_num
        LEFT JOIN (
        SELECT article_num, COUNT(*) AS heart_count
        FROM heart
        GROUP BY article_num
        ) AS heart_counts ON board.article_num = heart_counts.article_num
        LEFT JOIN (
        SELECT article_num, MAX(file_path) AS file_path
        FROM fileupload
        GROUP BY article_num
        ) AS fileupload ON board.article_num = fileupload.article_num
        LEFT JOIN member m ON board.user_id = m.user_id
        order by board.write_date desc  
        ")->result();

    }

    function select_board_delete($board, $category_num)
    {
        $this->db->query("update board set category_num = 0, board_status = 2, del_category = '$category_num' where article_num = '$board'");
    }

    function category_list()
    {
        return $this->db->query("select * from category")->result();
    }

    function count_board_list($category_num)
    {
        $this->db->where("category_num", $category_num);
        $this->db->where("board_status", 1);
        return $this->db->count_all_results('board');
    }

    function get_board_list($limit, $offset)
    {
        $this->db->order_by('write_date', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('board');
        return $query->result();
    }

    function select_board_move($sel_list, $select_category)
    {
        return $this->db->query("update board set category_num = '$select_category' where article_num = '$sel_list'");
    }

    function get_category_name($category_num)
    {
        $sql = $this->db->query("select * from category where category_num = '$category_num'")->result();

        foreach ($sql as $row) {
            return $row;
        }
    }


    function footer_search($category_num, $option1, $option2, $title, $limit, $offset)
    {
        // $sql = $this->db->query("select * from board where category_num = '$category_num' and ");
        $start_date = null;
        $end_date = date('Y-m-d');

        $sql = "with RECURSIVE PostHierarchy AS (
            SELECT DISTINCT
                article_num,
                category_num,
                title,
                parent_id,
                grp,
                seq,
                depth,
                write_date AS post_write_date
            FROM
                board
            WHERE
                parent_id = 0 
                AND category_num = '$category_num'
            UNION ALL
            SELECT
                b.article_num,
                b.category_num,
                b.title,
                b.parent_id,
                b.grp,
                b.seq,
                b.depth,
                ph.post_write_date
            FROM
                board b
            JOIN
                PostHierarchy ph ON b.parent_id = ph.article_num
        )
        SELECT DISTINCT
            ph.article_num,
            ph.category_num,
            ph.title,
            ph.parent_id,
            ph.grp,
            ph.seq,
            ph.depth,
            (SELECT COUNT(*) FROM board WHERE parent_id = ph.article_num) AS child_count,
            COALESCE(comment_counts.comment_count, 0) AS comment_count,
            COALESCE(heart_counts.heart_count, 0) AS heart_count,
            CASE WHEN (SELECT board_status FROM board WHERE article_num = ph.parent_id) = 2 THEN 0 ELSE 1 END AS parent_valid,
            (
                SELECT GROUP_CONCAT(DISTINCT fu.file_path SEPARATOR ', ')
                FROM fileupload fu
                WHERE fu.article_num = ph.article_num
            ) AS file_path,
            b.content,
            b.user_id,
            m.user_nickname,
            ph.post_write_date AS write_date,
            (SELECT c.content FROM comments c WHERE c.article_num = ph.article_num LIMIT 1) AS first_comment
        FROM
            PostHierarchy ph
        LEFT JOIN (
            SELECT article_num, COUNT(*) AS comment_count
            FROM comments
            GROUP BY article_num
        ) AS comment_counts ON ph.article_num = comment_counts.article_num
        LEFT JOIN (
            SELECT article_num, COUNT(*) AS heart_count
            FROM heart
            GROUP BY article_num
        ) AS heart_counts ON ph.article_num = heart_counts.article_num
        LEFT JOIN board b ON ph.article_num = b.article_num
        LEFT JOIN member m ON b.user_id = m.user_id
        LEFT JOIN comments c ON b.article_num = c.article_num
        WHERE b.category_num = '$category_num' AND b.board_status = 1 
        ";

        if ($option1 == "all") {
            // $sql .= " and '$start_date' <= board.write_date";
        } else if ($option1 == "1_day") {
            $start_date = date('Y-m-d', strtotime('-1 day', strtotime($end_date)));
        } else if ($option1 == "1_week") {
            $start_date = date('Y-m-d', strtotime('-1 week', strtotime($end_date)));
        } else if ($option1 == "1_months") {
            $start_date = date('Y-m-d', strtotime('-1 month', strtotime($end_date)));
        } else if ($option1 == "6_months") {
            $start_date = date('Y-m-d', strtotime('-6 months', strtotime($end_date)));
        } else if ($option1 == "1_year") {
            $start_date = date('Y-m-d', strtotime('-1 year', strtotime($end_date)));
        }

        if (!empty($start_date)) {
            $sql .= " and ph.post_write_date between '$start_date' and '$end_date'";
        }

        if ($option2 == "board_comment") {
            $sql .= " and (b.title LIKE '%$title%' OR b.content LIKE '%$title%' OR c.content LIKE '%$title%')";
        } elseif ($option2 == "title") {
            $sql .= " and b.title LIKE '%$title%'";
        } elseif ($option2 == "board_writer") {
            $sql .= " and b.user_id LIKE '%$title%'";
        } elseif ($option2 == "content") {
            $sql .= " and c.content LIKE '%$title%'";
        } elseif ($option2 == "comment_writer") {
            $sql .= " and c.user_id LIKE '%$title%'";
        }


        $sql .= "order by
        write_date desc,
        ph.grp,
        ph.seq
        LIMIT $offset,$limit";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }
    function footer_search_count($category_num, $option1, $option2, $title)
    {
        $start_date = null;
        $end_date = date('Y-m-d');

        $this->db->select('COUNT(*) AS result_count');
        $this->db->from('board');
        $this->db->join('comments', 'board.article_num = comments.article_num', 'left');
        $this->db->where('board.category_num', $category_num);
        $this->db->where('board.board_status', 1);

        if ($option1 == "1_day") {
            $start_date = date('Y-m-d', strtotime('-1 day', strtotime($end_date)));
        } else if ($option1 == "1_week") {
            $start_date = date('Y-m-d', strtotime('-1 week', strtotime($end_date)));
        } else if ($option1 == "1_months") {
            $start_date = date('Y-m-d', strtotime('-1 month', strtotime($end_date)));
        } else if ($option1 == "6_months") {
            $start_date = date('Y-m-d', strtotime('-6 months', strtotime($end_date)));
        } else if ($option1 == "1_year") {
            $start_date = date('Y-m-d', strtotime('-1 year', strtotime($end_date)));
        }

        if (!empty($start_date)) {
            $this->db->where("board.write_date BETWEEN '$start_date' AND '$end_date'");
        }

        if ($option2 == "board_comment") {
            $this->db->group_start();
            $this->db->like('board.title', $title);
            $this->db->or_like('board.content', $title);
            $this->db->or_like('comments.content', $title);
            $this->db->group_end();
        } elseif ($option2 == "title") {
            $this->db->like('board.title', $title);
        } elseif ($option2 == "board_writer") {
            $this->db->like('board.user_id', $title);
        } elseif ($option2 == "content") {
            $this->db->like('comments.content', $title);
        } elseif ($option2 == "comment_writer") {
            $this->db->like('comments.user_id', $title);
        }

        $query = $this->db->get();
        $row = $query->row();

        return $row->result_count;
    }



    function date_search_count($select_date, $category_num)
    {
        $sql = "select COUNT(*) as count FROM board WHERE write_date >= '$select_date 00:00:00' AND write_date <= '$select_date 23:59:59'AND category_num = '$category_num' AND board_status = 1;";

        return $this->db->query($sql)->row_array();
    }

    function date_search($select_date, $category_num, $per_page, $offset)
    {
        $sql = "select 
        b.*,
        f.file_path,
        IFNULL(h.heart_count, 0) AS heart_count,
        IFNULL(c.comment_count, 0) AS comment_count,
    FROM board AS b
    LEFT JOIN (
        SELECT article_num, MAX(file_path) AS file_path
        FROM fileupload
        GROUP BY article_num
    ) AS f ON b.article_num = f.article_num
    LEFT JOIN (
        SELECT article_num, COUNT(*) AS heart_count
        FROM heart
        GROUP BY article_num
    ) AS h ON b.article_num = h.article_num
    LEFT JOIN (
        SELECT article_num, COUNT(*) AS comment_count
        FROM comments
        GROUP BY article_num
    ) AS c ON b.article_num = c.article_num
    WHERE 
        b.write_date >= '$select_date 00:00:00'
        AND b.write_date <= '$select_date 23:59:59'
        AND b.board_status = 1
        AND b.category_num = '$category_num'
        limit $offset, $per_page;
    ";
    

        return $this->db->query($sql)->result();
    }

    function find_board($post_num)
    {
        $sql = "select * from board where article_num = '$post_num'";

        return $this->db->query($sql)->row();

    }
}