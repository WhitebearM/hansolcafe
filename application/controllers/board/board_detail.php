<?

class board_detail extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('user_agent');
        $this->load->library("layout_common");
        $this->load->model("/board/board_detail_model");
        $this->load->library("session");
        $this->load->library("form_validation");
        $this->load->helper('download');
        $this->load->helper("url");
        $this->load->database();
    }


    function index()
    {
        $user_id = $this->session->userdata("id");
        $id_authority = $this->session->userdata("id_authority");
        $category = $this->input->get("category");
        $board_num = $this->input->get("board_num");

        $board_detail_info = $this->board_detail_model->board_detail($board_num);
        if ($board_detail_info->disclosure == 1 && isset($user_id) && $board_detail_info != "") {
            if ($board_detail_info->board_status == 1) {
                $all_category = $this->board_detail_model->category_all();
                $category_info = $this->board_detail_model->category_info($category);

                $board_user_info = $this->board_detail_model->board_user_info($board_detail_info->user_id);
                // 좋아요
                $heart_check = $this->board_detail_model->board_heart_check($user_id, $board_num);

                $heart_count = $this->board_detail_model->board_hearts($board_num);
                $heart_count = $heart_count[0]->heart_count;

                //댓글 수
                $comments_num = $this->board_detail_model->board_comments($board_num);
                $comments_num = $comments_num[0]->comments_count;

                //해당 게시글 댓글 불러오기
                if (isset($_GET['comments_val']) && isset($_GET['board_num'])) {
                    $comments_val = $this->input->get("comments_val");

                    if ($comments_val == '최신순') {
                        $comments = $this->board_detail_model->re_new_comments_list($board_num);
                    } else if ($comments_val == '등록순') {
                        $comments = $this->board_detail_model->re_write_comments_list($board_num);
                    }
                } else {
                    $comments = $this->board_detail_model->board_comment($board_num);
                }
                //하단 해당 게시글의 게시글 최근글 5개
                $mini_list = $this->board_detail_model->board_semi_list($category);

                $file_info = $this->board_detail_model->get_board_file_info($board_num);

                if ($user_id) {
                    $this->layout_common->view(
                        "/board/board_detail_view",
                        array(
                            "user_info" => $board_user_info,
                            "user_id" => $user_id,
                            "board" => $board_detail_info,
                            "category" => $category_info,
                            "comments_num" => $comments_num,
                            "heart_num" => $heart_count,
                            "heart_check" => $heart_check,
                            "id_authority" => $id_authority,
                            "mini_list" => $mini_list,
                            "file_info" => $file_info,
                            "category_modify" => $all_category,
                            "comments" => $comments,
                            "board_num" => $board_num
                        )
                    );
                } else {
                    echo "<script>
                alert('로그인 후 이용 바랍니다.');
                location.href='/login/login';</script>";
                }
            } else {
                $category_info = $this->board_detail_model->category_info($category);
                echo "<script>
            alert('삭제된 게시물입니다.');
            location.href='/board/board_list?name=$category_info->category_name&num=$category_info->category_num';</script>";
            }

            // 공개범위 전체
        } else if ($board_detail_info->disclosure == 2 && !isset($user_id) || isset($user_id) && $board_detail_info != "") {
            $all_category = $this->board_detail_model->category_all();
            $category_info = $this->board_detail_model->category_info($category);

            $board_user_info = $this->board_detail_model->board_user_info($board_detail_info->user_id);
            // 좋아요
            $heart_check = $this->board_detail_model->board_heart_check($user_id, $board_num);

            $heart_count = $this->board_detail_model->board_hearts($board_num);
            $heart_count = $heart_count[0]->heart_count;

            //댓글 수
            $comments_num = $this->board_detail_model->board_comments($board_num);
            $comments_num = $comments_num[0]->comments_count;

            //해당 게시글 댓글 불러오기
            $comments = $this->board_detail_model->board_comment($board_num);

            //하단 해당 게시글의 게시글 최근글 5개
            $mini_list = $this->board_detail_model->board_semi_list($board_num);

            $file_info = $this->board_detail_model->get_board_file_info($board_num);

            $this->layout_common->view(
                "/board/board_detail_view",
                array(
                    "user_info" => $board_user_info,
                    "user_id" => $user_id,
                    "board" => $board_detail_info,
                    "category" => $category_info,
                    "comments_num" => $comments_num,
                    "heart_num" => $heart_count,
                    "heart_check" => $heart_check,
                    "id_authority" => $id_authority,
                    "mini_list" => $mini_list,
                    "file_info" => $file_info,
                    "category_modify" => $all_category,
                    "comments" => $comments,
                    "board_num" => $board_num
                )
            );
        } else if ($board_detail_info == "") {
            echo "<script>
            alert('오류가 발생했습니다.');
            history.back();</script>";
        } else if (!isset($board_detail_info->disclosure)) {
            echo "<script>
            alert('오류가 발생했습니다.');
            history.back();</script>";
        } else {
            echo "<script>
            alert('로그인후 확인가능합니다.');
            history.back();</script>";
        }
    }
    // 게시글 삭제 (실제삭제아님)
    function detail_delete()
    {
        $board_num = $this->input->get("board");
        $category_num = $this->input->get("category");

        $this->board_detail_model->delete($board_num, $category_num);

        echo "<script>
        alert('삭제되었습니다.');
        location.href='/board/board_list?num=$category_num';</script>";
    }
    // 카테고리이동
    function category_modify()
    {
        $category_num = $this->input->post("category_id");
        $article_num = $this->input->post("article_num");

        $this->board_detail_model->category_modify($category_num, $article_num);
    }
    // 공지글 등록 해제
    function change_main_status()
    {
        $article_num = $this->input->post("article_num");

        $this->board_detail_model->change_main_status($article_num);
    }
    // 댓글작성
    function add_comment()
    {
        $this->form_validation->set_rules('detail_comments_write_text', 'detail_comments_write_text', 'required');

        if ($this->form_validation->run() == FALSE) {
            redirect("/board/board_detail", "refresh");
        } else {
            $comment_content = $this->input->post("detail_comments_write_text");
            $comment_content = html_escape($comment_content);
            $comment_user_id = $this->input->post("user_id");
            $comment_article_num = $this->input->post("article_num");
            $comment_parent_id = $this->input->post("parent_Id");
            $detail_comment_file = $this->input->post("detail_comment_file");

            //댓글이미지 업로드부분
            $config['upload_path'] = './comment_image/'; //경로
            $config['allowed_types'] = 'gif|jpg|png'; //허용할 타입
            $config['max_size'] = 250;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('detail_comment_file')) { //파일을 넣지않았거나 업로드가 안된경우
                $data = $this->upload->data();
                $image_path = "";

                $this->board_detail_model->write_comment($comment_content, $comment_user_id, $comment_article_num, $comment_parent_id, $image_path);
            } else {
                $data = $this->upload->data();
                $image_path = $data['full_path']; //풀 파일경로
                $wep_root = "C:/cloneproject/ci";
                $image_path = str_replace($wep_root, "", $image_path);

                // 파일업로드 테이블에 insert
                $this->board_detail_model->write_comment($comment_content, $comment_user_id, $comment_article_num, $comment_parent_id, $image_path);

            }

            redirect($this->agent->referrer());
        }
    }
    // 댓글삭제
    function comment_delete()
    {
        $comment_num = $this->input->post("delete_comment");

        $this->board_detail_model->comment_delete($comment_num);
    }
    //댓글 수정
    function board_comment_update()
    {
        $category_num = $this->input->post("category_num");
        $board_num = $this->input->post("article_number");

        $this->form_validation->set_rules('detail_comment_num', 'detail_comment_num', 'required');
        $this->form_validation->set_rules('detail_comment_content', 'detail_comment_content', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo "<script>
            alert('댓글을 수정해주세요.');
            location.href='/board/board_detail?category=$category_num&board_num=$board_num';</script>";
        } else {
            $comment_num = $this->input->post("detail_comment_num");
            $content = $this->input->post("detail_comment_content");
            $content = html_escape($content);

            $this->board_detail_model->comment_update($comment_num, $content);

            $response = array(
                'category_num' => $category_num,
                'board_num' => $board_num
            );
            echo "<script>
            location.href='/board/board_detail?category=$category_num&board_num=$board_num';</script>";
        }
    }

    //좋아유~
    public function heart_up()
    {
        $this->form_validation->set_rules('article_num', 'article_num', 'required');

        if ($this->form_validation->run() == FALSE) {
            // 유효성 검사 실패 시 처리
        } else {
            $user_id = $this->session->userdata("id");
            $article_num = $this->input->post("article_num");
            $is_liked = $this->board_detail_model->toggle_like($article_num, $user_id);

            $response = array(
                'success' => true,
                'isLiked' => $is_liked,
                'heartCount' => $this->board_detail_model->get_heart_count($article_num)
            );

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // 댓글 답글달기
    function board_recomment_writes()
    {
        $this->form_validation->set_rules('detail_recomment_content', 'detail_recomment_content', 'required');
        $this->form_validation->set_rules('recomment_parentID', 'recomment_parentID', 'required');

        $article_num = $this->input->post("article_number");
        $category_num = $this->input->post("category_num");
        $comment_num = $this->input->post("detail_comment_num");

        if ($this->form_validation->run() == FALSE) {
            echo "<script>
            alert('오류 발생.');
            location.href='/board/board_detail?category=$category_num&board_num=$article_num';</script>";
        } else {
            $id = $this->session->userdata("id");

            $recomment_content = $this->input->post("detail_recomment_content");
            $recomment_content = html_escape($recomment_content);
            $recomment_parentId = $this->input->post("recomment_parentID");


            $this->board_detail_model->recomment_write($recomment_parentId, $recomment_content, $comment_num, $id, $article_num);

            echo "<script>
            location.href='/board/board_detail?category=$category_num&board_num=$article_num';</script>";
        }

    }

    // 첨부파일 다운로드
    function file_download()
    {
        $encode_file_path = $this->input->get("file_path");

        $file_path = urldecode($encode_file_path);

        if (file_exists($file_path)) {
            // 다운로드 헬퍼 로드

            // 파일명 추출
            $file_name = basename($file_path);

            // 파일 다운로드
            force_download($file_name, file_get_contents($file_path));
        } else {
            echo "<script>
            alert('파일을 찾을수 없습니다.');</script>";
        }
    }

    // 이전글
    function previous_post()
    {
        $user_id = $this->session->userdata("id");
        $id_authority = $this->session->userdata("id_authority");
        $category = $this->input->get("category");
        $board_num = $this->input->get("board_num");

        $board_detail_info = $this->board_detail_model->previous_post($board_num, $category);
        if ($board_detail_info->disclosure == 1 && isset($user_id) && $board_detail_info != "") {
            if ($board_detail_info->board_status == 1) {
                $all_category = $this->board_detail_model->category_all();
                $category_info = $this->board_detail_model->category_info($category);

                $board_user_info = $this->board_detail_model->board_user_info($board_detail_info->user_id);
                // 좋아요
                $heart_check = $this->board_detail_model->board_heart_check($user_id, $board_num);

                $heart_count = $this->board_detail_model->board_hearts($board_num);
                $heart_count = $heart_count[0]->heart_count;

                //댓글 수
                $comments_num = $this->board_detail_model->board_comments($board_num);
                $comments_num = $comments_num[0]->comments_count;

                //해당 게시글 댓글 불러오기
                $comments = $this->board_detail_model->board_comment($board_num);

                //하단 해당 게시글의 게시글 최근글 5개
                $mini_list = $this->board_detail_model->board_semi_list($board_num);

                $file_info = $this->board_detail_model->get_board_file_info($board_num);

                if ($user_id) {
                    $this->layout_common->view(
                        "/board/board_detail_view",
                        array(
                            "user_info" => $board_user_info,
                            "user_id" => $user_id,
                            "board" => $board_detail_info,
                            "category" => $category_info,
                            "comments_num" => $comments_num,
                            "heart_num" => $heart_count,
                            "heart_check" => $heart_check,
                            "id_authority" => $id_authority,
                            "mini_list" => $mini_list,
                            "file_info" => $file_info,
                            "category_modify" => $all_category,
                            "comments" => $comments,
                            "board_num" => $board_num
                        )
                    );
                } else {
                    echo "<script>
                alert('로그인 후 이용 바랍니다.');
                location.href='/login/login';</script>";
                }
            } else {
                $category_info = $this->board_detail_model->category_info($category);
                echo "<script>
            alert('삭제된 게시물입니다.');
            location.href='/board/board_list?name=$category_info->category_name&num=$category_info->category_num';</script>";
            }

            // 공개범위 전체
        } else if ($board_detail_info->disclosure == 2 && !isset($user_id) || isset($user_id) && $board_detail_info != "") {
            $all_category = $this->board_detail_model->category_all();
            $category_info = $this->board_detail_model->category_info($category);

            $board_user_info = $this->board_detail_model->board_user_info($board_detail_info->user_id);
            // 좋아요
            $heart_check = $this->board_detail_model->board_heart_check($user_id, $board_num);

            $heart_count = $this->board_detail_model->board_hearts($board_num);
            $heart_count = $heart_count[0]->heart_count;

            //댓글 수
            $comments_num = $this->board_detail_model->board_comments($board_num);
            $comments_num = $comments_num[0]->comments_count;

            //해당 게시글 댓글 불러오기
            $comments = $this->board_detail_model->board_comment($board_num);

            //하단 해당 게시글의 게시글 최근글 5개
            $mini_list = $this->board_detail_model->board_semi_list($board_num);

            $file_info = $this->board_detail_model->get_board_file_info($board_num);

            $this->layout_common->view(
                "/board/board_detail_view",
                array(
                    "user_info" => $board_user_info,
                    "user_id" => $user_id,
                    "board" => $board_detail_info,
                    "category" => $category_info,
                    "comments_num" => $comments_num,
                    "heart_num" => $heart_count,
                    "heart_check" => $heart_check,
                    "id_authority" => $id_authority,
                    "mini_list" => $mini_list,
                    "file_info" => $file_info,
                    "category_modify" => $all_category,
                    "comments" => $comments,
                    "board_num" => $board_num
                )
            );
        } else if ($board_detail_info == "") {
            echo "<script>
                alert('존재하지않는 게시물입니다.');</script>";

            redirect($this->agent->referrer());

        } else if (!isset($board_detail_info->disclosure)) {
            echo "<script>
                alert('존재하지않는 게시물입니다.');</script>";

            redirect($this->agent->referrer());
        } else {
            echo "<script>
            alert('접근할수 없는 환경입니다.');</script>";

            redirect($this->agent->referrer());
        }
    }

    // 다음글
    function next_post()
    {
        $user_id = $this->session->userdata("id");
        $id_authority = $this->session->userdata("id_authority");
        $category = $this->input->get("category");
        $board_num = $this->input->get("board_num");

        $board_detail_info = $this->board_detail_model->next_post($board_num, $category);
        /* while($board_detail_info->board_status == 2){
            $board_num =+ 1;
            $board_detail_info = $this->board_detail_model->next_post($board_num, $category);
        } */
        if ($board_detail_info->disclosure == 1 && isset($user_id) && $board_detail_info != "") {
            if ($board_detail_info->board_status == 1) {
                $all_category = $this->board_detail_model->category_all();
                $category_info = $this->board_detail_model->category_info($category);

                $board_user_info = $this->board_detail_model->board_user_info($board_detail_info->user_id);
                // 좋아요
                $heart_check = $this->board_detail_model->board_heart_check($user_id, $board_num);

                $heart_count = $this->board_detail_model->board_hearts($board_num);
                $heart_count = $heart_count[0]->heart_count;

                //댓글 수
                $comments_num = $this->board_detail_model->board_comments($board_num);
                $comments_num = $comments_num[0]->comments_count;

                //해당 게시글 댓글 불러오기
                $comments = $this->board_detail_model->board_comment($board_num);

                //하단 해당 게시글의 게시글 최근글 5개
                $mini_list = $this->board_detail_model->board_semi_list($board_num);

                $file_info = $this->board_detail_model->get_board_file_info($board_num);

                if ($user_id) {
                    $this->layout_common->view(
                        "/board/board_detail_view",
                        array(
                            "user_info" => $board_user_info,
                            "user_id" => $user_id,
                            "board" => $board_detail_info,
                            "category" => $category_info,
                            "comments_num" => $comments_num,
                            "heart_num" => $heart_count,
                            "heart_check" => $heart_check,
                            "id_authority" => $id_authority,
                            "mini_list" => $mini_list,
                            "file_info" => $file_info,
                            "category_modify" => $all_category,
                            "comments" => $comments,
                            "board_num" => $board_num
                        )
                    );
                } else {
                    echo "<script>
                alert('로그인 후 이용 바랍니다.');
                location.href='/login/login';</script>";
                }
            } else if ($board_detail_info->board_status == 2) {
                $category_info = $this->board_detail_model->category_info($category);
                echo "<script>
            alert('삭제된 게시물입니다.');
            location.href='/board/board_list?name=$category_info->category_name&num=$category_info->category_num';</script>";
            }

            // 공개범위 전체
        } else if ($board_detail_info->disclosure == 2 && !isset($user_id) || isset($user_id) && $board_detail_info != "") {
            $all_category = $this->board_detail_model->category_all();
            $category_info = $this->board_detail_model->category_info($category);

            $board_user_info = $this->board_detail_model->board_user_info($board_detail_info->user_id);
            // 좋아요
            $heart_check = $this->board_detail_model->board_heart_check($user_id, $board_num);

            $heart_count = $this->board_detail_model->board_hearts($board_num);
            $heart_count = $heart_count[0]->heart_count;

            //댓글 수
            $comments_num = $this->board_detail_model->board_comments($board_num);
            $comments_num = $comments_num[0]->comments_count;

            //해당 게시글 댓글 불러오기
            $comments = $this->board_detail_model->board_comment($board_num);

            //하단 해당 게시글의 게시글 최근글 5개
            $mini_list = $this->board_detail_model->board_semi_list($board_num);

            $file_info = $this->board_detail_model->get_board_file_info($board_num);

            $this->layout_common->view(
                "/board/board_detail_view",
                array(
                    "user_info" => $board_user_info,
                    "user_id" => $user_id,
                    "board" => $board_detail_info,
                    "category" => $category_info,
                    "comments_num" => $comments_num,
                    "heart_num" => $heart_count,
                    "heart_check" => $heart_check,
                    "id_authority" => $id_authority,
                    "mini_list" => $mini_list,
                    "file_info" => $file_info,
                    "category_modify" => $all_category,
                    "comments" => $comments,
                    "board_num" => $board_num
                )
            );
        } else if ($board_detail_info == "") {
            echo "<script>
                alert('존재하지않는 게시물입니다.');</script>";

            redirect($this->agent->referrer());

        } else if (!isset($board_detail_info->disclosure)) {
            echo "<script>
                alert('존재하지않는 게시물입니다.');</script>";

            redirect($this->agent->referrer());
        } else {
            echo "<script>
            alert('접근할수 없는 환경입니다.');</script>";

            redirect($this->agent->referrer());
        }
    }

    function re_comments_list()
    {

        $user_id = $this->session->userdata("id");
        $id_authority = $this->session->userdata("id_authority");
        $category = $this->input->get("category");
        $board_num = $this->input->get("board_num");

        $board_detail_info = $this->board_detail_model->board_detail($board_num);
        if ($board_detail_info->disclosure == 1 && isset($user_id) && $board_detail_info != "") {
            if ($board_detail_info->board_status == 1) {
                $all_category = $this->board_detail_model->category_all();
                $category_info = $this->board_detail_model->category_info($category);

                $board_user_info = $this->board_detail_model->board_user_info($board_detail_info->user_id);
                // 좋아요
                $heart_check = $this->board_detail_model->board_heart_check($user_id, $board_num);

                $heart_count = $this->board_detail_model->board_hearts($board_num);
                $heart_count = $heart_count[0]->heart_count;

                //댓글 수
                $comments_num = $this->board_detail_model->board_comments($board_num);
                $comments_num = $comments_num[0]->comments_count;

                //해당 게시글 댓글 불러오기
                if (isset($_GET['comments_val']) && isset($_GET['board_num'])) {
                    $comments_val = $this->input->get("comments_val");
                    $board_num = $this->input->get("board_num");

                    if ($comments_val == '최신순') {
                        $comments = $this->board_detail_model->re_new_comments_list($board_num);
                    } else if ($comments_val == '등록순') {
                        $comments = $this->board_detail_model->re_write_comments_list($board_num);
                    }
                } else {
                    $comments = $this->board_detail_model->board_comment($board_num);
                }
                //하단 해당 게시글의 게시글 최근글 5개
                $mini_list = $this->board_detail_model->board_semi_list($board_num);

                $file_info = $this->board_detail_model->get_board_file_info($board_num);

                if ($user_id) {
                    $this->layout_common->view(
                        "/board/board_detail_view",
                        array(
                            "user_info" => $board_user_info,
                            "user_id" => $user_id,
                            "board" => $board_detail_info,
                            "category" => $category_info,
                            "comments_num" => $comments_num,
                            "heart_num" => $heart_count,
                            "heart_check" => $heart_check,
                            "id_authority" => $id_authority,
                            "mini_list" => $mini_list,
                            "file_info" => $file_info,
                            "category_modify" => $all_category,
                            "comments" => $comments,
                            "board_num" => $board_num
                        )
                    );
                } else {
                    echo "<script>
                alert('로그인 후 이용 바랍니다.');
                location.href='/login/login';</script>";
                }
            } else {
                $category_info = $this->board_detail_model->category_info($category);
                echo "<script>
            alert('삭제된 게시물입니다.');
            location.href='/board/board_list?name=$category_info->category_name&num=$category_info->category_num';</script>";
            }

            // 공개범위 전체
        } else if ($board_detail_info->disclosure == 2 && !isset($user_id) || isset($user_id) && $board_detail_info != "") {
            $all_category = $this->board_detail_model->category_all();
            $category_info = $this->board_detail_model->category_info($category);

            $board_user_info = $this->board_detail_model->board_user_info($board_detail_info->user_id);
            // 좋아요
            $heart_check = $this->board_detail_model->board_heart_check($user_id, $board_num);

            $heart_count = $this->board_detail_model->board_hearts($board_num);
            $heart_count = $heart_count[0]->heart_count;

            //댓글 수
            $comments_num = $this->board_detail_model->board_comments($board_num);
            $comments_num = $comments_num[0]->comments_count;

            //해당 게시글 댓글 불러오기
            $comments = $this->board_detail_model->board_comment($board_num);

            //하단 해당 게시글의 게시글 최근글 5개
            $mini_list = $this->board_detail_model->board_semi_list($board_num);

            $file_info = $this->board_detail_model->get_board_file_info($board_num);

            $this->layout_common->view(
                "/board/board_detail_view",
                array(
                    "user_info" => $board_user_info,
                    "user_id" => $user_id,
                    "board" => $board_detail_info,
                    "category" => $category_info,
                    "comments_num" => $comments_num,
                    "heart_num" => $heart_count,
                    "heart_check" => $heart_check,
                    "id_authority" => $id_authority,
                    "mini_list" => $mini_list,
                    "file_info" => $file_info,
                    "category_modify" => $all_category,
                    "comments" => $comments,
                    "board_num" => $board_num
                )
            );
        } else if ($board_detail_info == "") {
            echo "<script>
                alert('존재하지않는 게시물입니다.');</script>";

            redirect($this->agent->referrer());

        } else if (!isset($board_detail_info->disclosure)) {
            echo "<script>
                alert('존재하지않는 게시물입니다.');</script>";

            redirect($this->agent->referrer());
        } else {
            echo "<script>
            alert('접근할수 없는 환경입니다.');</script>";

            redirect($this->agent->referrer());
        }
    }

}