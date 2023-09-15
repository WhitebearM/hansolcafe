<?

class layout_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function selAdmin()
    {
        $sql = $this->db->query("select * from member where user_id = 'admin' and authority = '2' ");

        foreach ($sql->result() as $row) {
            return $row;
        }
    }

    function date_desc_board()
    {
        return $this->db->query("select * from board order by write_date desc limit 5")->result();

    }

    function selmember($id)
    {
        $sql = $this->db->query("select * from member where user_id = '$id'");

        foreach ($sql->result() as $row) {
            return $row;
        }
    }

    function count()
    {
        $sql = $this->db->query("select count(*) as total_member from member");

        foreach ($sql->result() as $row) {
            return $row;
        }
    }

    function board_count($id)
    {
        $sql = $this->db->query("select count(*) as total_board from board where user_id = '$id'");

        foreach ($sql->result() as $row) {
            return $row;
        }
    }

    function comment_count($id)
    {
        $sql = $this->db->query("select count(*) as total_comments from comments where user_id = '$id'");

        foreach ($sql->result() as $row) {
            return $row;
        }
    }

    function category_insert($category_name)
    {
        $this->db->query("insert into category(category_name) values('$category_name')");
    }

    function category_list()
    {
        $sql = $this->db->query("select * from category");

        $category = array();

        foreach ($sql->result() as $list) {
            $category[] = $list;
        }
        return $category;
    }

    function ck_category($category)
    {
        $sql = $this->db->query("select category_name from category where category_name = '$category'");

        foreach ($sql->result() as $row) {
            return $row;
        }
    }

    function delete($category_num)
    {
        $this->db->query("update board set category_num = '0' where category_num = '$category_num'");
        $this->db->query("delete from category where category_num = '$category_num'");
    }

    function main_list()
    {
        $sql = $this->db->query("select 
        board.*,
        COALESCE(comment_counts.comment_count, 0) AS comment_count,
        COALESCE(heart_counts.heart_count, 0) AS heart_count,
        fileupload.file_path 
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
        LEFT JOIN fileupload ON board.article_num = fileupload.article_num
        order by board.write_date desc
        limit 10
        ");

        return $sql->result();
    }

    function main_list_pagi($limit, $offset)
    {
        $sql = $this->db->query("select 
    board.*,
    COALESCE(comment_counts.comment_count, 0) AS comment_count,
    COALESCE(heart_counts.heart_count, 0) AS heart_count,
    COALESCE(GROUP_CONCAT(fileupload.file_path SEPARATOR ', '), '') AS file_path
    FROM
    board
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
    LEFT JOIN fileupload ON board.article_num = fileupload.article_num
    WHERE
    board.board_status = 1
    GROUP BY
    board.article_num
    ORDER BY
    board.write_date DESC
    LIMIT $offset, $limit;
    
        ");

        return $sql->result();
    }

    function ck_modify_mem($id)
    {
        $sql = $this->db->query("select * from member where user_id = '$id'")->result();

        foreach ($sql as $row) {
            return $row;
        }

    }

    function main_board_search($option1, $option2, $title, $limit, $offset)
    {
        $start_date = null;
        $end_date = date('Y-m-d');

        $sql = "select DISTINCT board.*, COALESCE(comment_counts.comment_count, 0) AS comment_count, COALESCE(heart_counts.heart_count, 0) AS heart_count, fileupload.file_path
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
        LEFT JOIN fileupload ON board.article_num = fileupload.article_num
        LEFT JOIN comments c ON board.article_num = c.article_num
        WHERE board.board_status = 1
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
            $sql .= " and board.write_date between '$start_date' and '$end_date'";
        }

        if ($option2 == "board_comment") {
            $sql .= " and (board.title LIKE '%$title%' OR board.content LIKE '%$title%' OR c.content LIKE '%$title%')";
        } elseif ($option2 == "title") {
            $sql .= " and board.title LIKE '%$title%'";
        } elseif ($option2 == "board_writer") {
            $sql .= " and board.user_id LIKE '%$title%'";
        } elseif ($option2 == "content") {
            $sql .= " and c.content LIKE '%$title%'";
        } elseif ($option2 == "comment_writer") {
            $sql .= " and c.user_id LIKE '%$title%'";
        }

        $sql .= "LIMIT $offset,$limit";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }
    function main_board_search_count($option1, $option2, $title)
    {
        $start_date = null;
        $end_date = date('Y-m-d');

        $this->db->select('COUNT(*) AS result_count');
        $this->db->from('board');
        $this->db->join('comments', 'board.article_num = comments.article_num', 'left');
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

    function header_search_count($search_title)
    {
        $this->db->from("board");
        $this->db->where("board_status", 1);
        $this->db->like("title", $search_title);
        $this->db->or_like("content", $search_title);
        $this->db->or_like("user_id", $search_title);

        return $this->db->count_all_results();
    }

    function layout_board_count()
    {
        $this->db->from("board");
        $this->db->where("board_status", 1);

        return $this->db->count_all_results();
    }

    function header_search_list($per_page, $offset, $search_title)
    {

        $this->db->distinct();
        $this->db->select('board.*, COALESCE(comment_counts.comment_count, 0) AS comment_count, COALESCE(heart_counts.heart_count, 0) AS heart_count, fileupload.file_path');
        $this->db->from('board');
        $this->db->join('(SELECT article_num, COUNT(*) AS comment_count FROM comments GROUP BY article_num) AS comment_counts', 'board.article_num = comment_counts.article_num', 'left');
        $this->db->join('(SELECT article_num, COUNT(*) AS heart_count FROM heart GROUP BY article_num) AS heart_counts', 'board.article_num = heart_counts.article_num', 'left');
        $this->db->join('fileupload', 'board.article_num = fileupload.article_num', 'left');
        $this->db->like('board.title', $search_title);
        $this->db->or_like('board.content', $search_title);
        $this->db->or_like('board.user_id', $search_title);
        $this->db->where('board.board_status', 1);
        $this->db->order_by('board.write_date', 'DESC');
        $this->db->limit($per_page, $offset);

        return $this->db->get()->result();
    }

    function gongji_board()
    {
        return $this->db->query("select 
        board.*,
        COALESCE(comment_counts.comment_count, 0) AS comment_count,
        COALESCE(heart_counts.heart_count, 0) AS heart_count,
        fileupload.file_path 
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
        LEFT JOIN fileupload ON board.article_num = fileupload.article_num
        order by board.write_date desc")->result();
    }
}