<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="/assets/css/board/board_list.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/board/board_list.js"></script>
</head>

<body>
    <div class="board_main">
        <div id="board_optin">
            <div>
                <div>
                    <div class="category_list">
                        <? if ($authority == 2) { ?>
                            <span class="category">
                                <h6>
                                    <?= $category_name ?>
                                </h6>
                            </span>
                        <? } else if ($authority == 1) { ?>
                                <span class="category">
                                    <h6>
                                    <?= $category_name ?>
                                    </h6>
                            <? } else { ?>
                                    <span class="category">
                                        <h6>
                                        <?= $category_name ?>
                                        </h6>
                                    </span>
                            <? } ?>
                            <div class="right_right">

                                <span class="option_222"><input class="form-check-input" type="checkbox" value=""
                                        id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        공지사항 숨기기
                                    </label>
                                </span>
                                <? if ($authority == 2 && isset($id)) { ?>
                                    <span><button type="button" id="category_modify2"
                                            class="btn btn-outline-secondary top_modify_category" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop_k">이동</button>
                                    </span>
                                    <span><button type="button" id="header_select_delete_btn"
                                            class="btn btn-outline-secondary">삭제</button>
                                    </span>
                                <? } ?>

                                <input type="hidden" class="category_num" value="<?= $category_num ?>">
                                <input type="hidden" class="category_name" value="<?= $category_name ?>">

                                <? if ($division == false) { ?>
                                    <? if (isset($option1) && isset($option2) && isset($option3)) { ?>
                                        <input type="hidden" class="option1" value="<?= $option1 ?>">
                                        <input type="hidden" class="option2" value="<?= $option2 ?>">
                                        <input type="hidden" class="option3" value="<?= $option3 ?>">
                                    <? } ?>
                                    <span class="option_right">
                                        <select id="footer_search_select_number" class="form-select"
                                            aria-label="Default select example" onchange="changeSearchPerpage(this)">
                                            <option value="5" <?= ($per_page == 5) ? 'selected' : '' ?>>5개</option>
                                            <option value="10" <?= ($per_page == 10) ? 'selected' : '' ?>>10개</option>
                                            <option value="15" <?= ($per_page == 15) ? 'selected' : '' ?>>15개</option>
                                            <option value="20" <?= ($per_page == 20) ? 'selected' : '' ?>>20개</option>
                                            <option value="25" <?= ($per_page == 25) ? 'selected' : '' ?>>25개</option>
                                            <option value="30" <?= ($per_page == 30) ? 'selected' : '' ?>>30개</option>
                                        </select>
                                    </span>
                                <? } else if ($division == true) { ?>
                                        <span class="option_right">
                                            <select id="board_select_number" class="form-select"
                                                aria-label="Default select example" onchange="changePerpage(this)">
                                                <option value="5" <?= ($per_page == 5) ? 'selected' : '' ?>>5개</option>
                                                <option value="10" <?= ($per_page == 10) ? 'selected' : '' ?>>10개</option>
                                                <option value="15" <?= ($per_page == 15) ? 'selected' : '' ?>>15개</option>
                                                <option value="20" <?= ($per_page == 20) ? 'selected' : '' ?>>20개</option>
                                                <option value="25" <?= ($per_page == 25) ? 'selected' : '' ?>>25개</option>
                                                <option value="30" <?= ($per_page == 30) ? 'selected' : '' ?>>30개</option>
                                            </select>
                                        </span>
                                <? } ?>
                            </div>
                    </div>
                </div>
            </div>
            <div class="title_Benchmark">
                <span class="title_title text-center">제목</span>
                <span class="title_writer text-center">작성자</span>
                <span class="title_comment text-center">댓글</span>
                <span class="title_date text-center">작성일</span>
                <span class="title_heart text-center">좋아요</span>
            </div>
            <div>
                <div>
                    <div>
                        <? foreach ($all_board as $gongji_board) { ?>
                            <? if ($gongji_board->main_status == 2 && $gongji_board->board_status == 1) { ?>
                                <div class="main_gongji">
                                    <ul id="main_status_move_b">
                                        <li id="main_status_b">공지</li>
                                        <li id="main_title_b">
                                            <a
                                                href="/board/board_detail?category=<?= $category_num ?>&board_num=<?= $gongji_board->article_num ?>"><?= $gongji_board->title ?></a>
                                        </li>
                                        <li id="main_name_b">
                                            <?= $gongji_board->user_id ?>
                                        </li>
                                        <li id="main_common_b">
                                            <?= $gongji_board->comment_count ?>
                                        </li>
                                        <li id="main_te_b">
                                            <?= date('Y-m-d', strtotime($gongji_board->write_date)) ?>
                                        </li>
                                        <li id="main_heart_b">
                                            <?= $gongji_board->heart_count ?>
                                        </li>
                                    </ul>
                                </div>
                            <? } ?>
                        <? } ?>
                        <div class="board_list_ha">
                            <form action="/board/board_list/sel_delete_board" method="post" id="select_delete_form">
                                <? foreach ($result as $board) { ?>

                                    <div class="board_list">
                                        <? if ($authority == 2 && isset($id)) { ?>
                                            <span id="board_check">
                                                <input type="checkbox" value="<?= $board->article_num ?>"
                                                    name="selected_board[]" class="postcheckbox">
                                                <input type="hidden" class="exception_article_num" name="exception_article_num"
                                                    value="<?= $category_num ?>">
                                                <input type="hidden" class="exception_article_name"
                                                    name="exception_article_name" value="<?= $category_name ?>">
                                                <input type="hidden" class="exception_category_num"
                                                    name="exception_category_num" value="<?= $board->category_num ?>">
                                            </span>
                                        <? } else { ?>
                                            <span id="nonecheked">
                                                <input type="checkbox" id="gost_ck">
                                            </span>

                                        <? } ?>


                                        <?
                                        if ($board->depth != 0) {
                                            $depth = $board->depth;
                                            $margin_left = ($depth + 1) * 20;
                                        } else {
                                            $margin_left = 0;
                                        }
                                        ?>
                                        <span id="board_write_title">
                                            <a style="margin-left:<?= $margin_left ?>px;"
                                                href="/board/board_detail?category=<?= $category_num ?>&board_num=<?= $board->article_num ?>">
                                                <? if ($board->depth != "0") { ?>
                                                    └
                                                <? } ?>
                                                <?= $board->title ?>
                                            </a>
                                            <? if (!$board->file_path == "") { ?>
                                                <img src="/assets/images/fileimg.png" width="20px" height="20px">
                                            <? } ?>
                                            <? if (strpos($board->content, "<img")) { ?>
                                                <img src="/assets/images/img.png" width="20px" height="20px">
                                            <? } ?>
                                            <? if ($board->comment_count != 0) { ?>
                                                <span class="title_right_color">[<?= $board->comment_count ?>]
                                                </span>
                                            <? } else { ?>

                                            <? } ?>
                                            <span id="new_board">
                                                <?= (strtotime('now') - strtotime($board->write_date)) / (60 * 60) < 12 ? "[new]" : ""; ?>
                                            </span>
                                        </span>
                                        <span id="board_write_name" class="text-center">
                                            <?= $board->user_id ?>
                                        </span>
                                        <span id="board_comment" class="text-center">
                                            <?= $board->comment_count ?>
                                        </span>
                                        <span id="board_date" class="text-center">
                                            <?= (strtotime('now') - strtotime($board->write_date)) / (60 * 60) < 12 ? date('H:i', strtotime($board->write_date)) : date('Y-m-d', strtotime($board->write_date)); ?>
                                        </span>
                                        <span id="board_heart" class="text-center">
                                            <?= $board->heart_count ?>
                                        </span>
                                    </div>
                                <? } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer_button">
            <ul id="footer_option">
                <? if ($authority == 2 && isset($id)) { ?>
                    <li class="all_ck_left"><input type="checkbox" name="board_check" class="board_ck"
                            onclick="selected_ck(this)">전체선택</li>
                <? } ?>
                <li>
                    <?= $pagenation ?>
                </li>
                <li class="board_btn_right">
                    <? if ($authority == 2 && isset($id)) { ?>
                        <button type="button" id="category_modify1" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop_k">이동</button>
                        <button type="submit" class="btn btn-outline-secondary" id="list_board_delete">삭제</button>
                    <? } ?>
                    <? if (isset($id)) { ?>
                        <a href="/board/board_write"><button type="button" class="btn btn-outline-secondary"
                                id="list_board_write">글쓰기</button></a>
                    <? } ?>
                </li>
            </ul>


            </form>
        </div>

        <div id="category_board_search">
            <form id="footer_search_form" action="/board/board_list/footer_search" method="get">
                <select name="category_option_1">
                    <option value="all">전체</option>
                    <option value="1_day">1일</option>
                    <option value="1_week">일주일</option>
                    <option value="1_months">1개월</option>
                    <option value="6_months">6개월</option>
                    <option value="1_year">1년</option>
                </select>

                <select name="category_option_2">
                    <option value="board_comment">게시글 + 댓글</option>
                    <option value="title">제목만</option>
                    <option value="board_writer">글작성자</option>
                    <option value="content">댓글내용</option>
                    <option value="comment_writer">댓글 작성자</option>
                </select>

                <input type="hidden" name="footer_search_categoryNum" value="<?= $category_num ?>">
                <input type="hidden" name="footer_search_categoryName" value="<?= $category_name ?>">

                <input type="text" name="board_footer_search" id="footer_search_gogo">
                <button type="submit">검색</button>
            </form>
        </div>

        <!-- 카테고리 이동 모달창 -->
        <div class="modal fade" id="staticBackdrop_k" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">카테고리 이동</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/board/board_list/board_category_move" method="post" id="sel_modify_form">
                            <select id="board_list_selected" name="sel_category">
                                <? foreach ($category_list as $cgy_list) { ?>
                                    <? if ($cgy_list->category_num != 0) { ?>
                                        <option value="<?= $cgy_list->category_num ?>"><?= $cgy_list->category_name ?>
                                        </option>
                                    <? } ?>
                                <? } ?>
                            </select>
                            <div id="delete_btn">
                                <button type="submit" class="btn btn-secondary" id="category_modify">이동</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</body>