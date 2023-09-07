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
            <table>
                <tr>
                    <? if ($authority == 2) { ?>
                        <th class="category">
                            <h5 id="category_title1">
                                <?= $category_name ?>
                            </h5>
                        </th>

                    <? } else if ($authority == 1) { ?>
                            <th class="category">
                                <h5 id="category_title2">
                                <?= $category_name ?>
                                </h5>
                            </th>
                    <? } else { ?>
                            <h5 id="category_title2">
                            <?= $category_name ?>
                            </h5>
                    <? } ?>
                    <th>
                        <div id="option_right1"><input class="form-check-input" type="checkbox" value=""
                                id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                공지사항 숨기기
                            </label>
                        </div>
                    </th>

                    <? if ($authority == 2 && isset($id)) { ?>
                        <th>
                            <div id="option_right2"><button type="button" id="category_modify2"
                                    class="btn btn-outline-secondary top_modify_category" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop_k">이동</button>
                            </div>
                        </th>
                        <th>
                            <div id="option_right3"><button type="button" id="header_select_delete_btn"
                                    class="btn btn-outline-secondary">삭제</button>
                            </div>
                        </th>
                    <? } ?>
                    <? if ($division == 1) { ?>
                        <th class="option_right">
                            <input type="hidden" class="category_num" value="<?= $category_num ?>">
                            <input type="hidden" class="category_name" value="<?= $category_name ?>">
                            <select id="board_select_number" class="form-select" aria-label="Default select example">
                                <option value="5" <?= ($per_page == 5) ? 'selected' : '' ?>>5개</option>
                                <option value="10" <?= ($per_page == 10) ? 'selected' : '' ?>>10개</option>
                                <option value="15" <?= ($per_page == 15) ? 'selected' : '' ?>>15개</option>
                                <option value="20" <?= ($per_page == 20) ? 'selected' : '' ?>>20개</option>
                                <option value="25" <?= ($per_page == 25) ? 'selected' : '' ?>>25개</option>
                                <option value="30" <?= ($per_page == 30) ? 'selected' : '' ?>>30개</option>
                            </select>
                        </th>
                    <? } ?>

                </tr>
            </table>
        </div>
        <div>
            <div class="title_Benchmark">
                <table id="title">
                    <tr>
                        <td id="title_main">제목</td>
                        <td id="title_name">작성자</td>
                        <td class="title_td">댓글</td>
                        <td class="title_td">작성일</td>
                        <td class="title_td">좋아요</td>
                    </tr>
                </table>
            </div>

            <div>
                <table>
                    <div id="main_gongji">
                        <? foreach ($all_board as $gongji_board) { ?>
                            <? if ($gongji_board->main_status == 2 && $gongji_board->board_status == 1) { ?>
                                <div id="main_status_move_b">
                                    <ul>
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
                                            <?= date('Y-m-d H:i', strtotime($gongji_board->write_date)) ?>
                                        </li>
                                        <li>
                                            <?= $gongji_board->heart_count ?>
                                        </li>
                                    </ul>
                                </div>
                            <? } ?>
                        <? } ?>
                    </div>
                    <div class="board_list_ha">
                        <form action="/board/board_list/sel_delete_board" method="post" id="select_delete_form">
                            <? foreach ($result as $board) { ?>

                                <tr class="board_list">
                                    <? if ($authority == 2) { ?>
                                        <th>
                                            <div id="board_check">
                                                <input type="checkbox" value="<?= $board->article_num ?>" name="selected_board[]" class="postcheckbox">
                                                <input type="hidden" class="exception_article_num" name="exception_article_num"
                                                    value="<?= $category_num ?>">
                                                <input type="hidden" class="exception_article_name" name="exception_article_name"
                                                    value="<?= $category_name ?>">
                                                <input type="hidden" class="exception_category_num" name="exception_category_num"
                                                    value="<?= $board->category_num?>">    
                                            </div>
                                        </th>
                                    <? } else { ?>
                                        <th id="nonecheked">

                                        </th>

                                    <? } ?>


                                    <? $depth = $board->depth;
                                        $margin_left = ($depth + 1) * 25;
                                    ?>
                                    <th id="board_write_title">
                                        <a style="margin-left:<?= $margin_left ?>px;" href="/board/board_detail?category=<?= $category_num ?>&board_num=<?= $board->article_num ?>">
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
                                    </th>
                                    <th id="board_write_name">
                                        <?= $board->user_id ?>
                                    </th>
                                    <th id="board_comment">
                                        <?= $board->comment_count ?>
                                    </th>
                                    <th id="board_date">
                                        <?= (strtotime('now') - strtotime($board->write_date)) / (60 * 60) < 12 ? date('H:i', strtotime($board->write_date)) : date('Y-m-d', strtotime($board->write_date)); ?>
                                    </th>
                                    <th id="board_heart">
                                        <?= $board->heart_count ?>
                                    </th>
                                </tr>
                            <? } ?>
                    </div>

                </table>
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
                    <button type="submit" class="btn btn-outline-secondary">삭제</button>
                <? } ?>
                <? if (isset($id)) { ?>
                    <a href="/board/board_write"><button type="button" class="btn btn-outline-secondary">글쓰기</button></a>
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