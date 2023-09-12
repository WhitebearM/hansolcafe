<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="/assets/css/main/main.css">
    <script src="/assets/js/main/main.js"></script>
</head>

<body>
    <? if ($ct_num == 1) { ?>
        <!-- 최초 layout전체글보기 -->
        <div class="board_main">
            <div class="board_title">
                <ul>
                    <li>
                        <h5>전체 글보기</h5>
                    </li>
                    <li id="main_title_move_btn"><a href="/layout/full_board_list">더보기 ></a></li>
                </ul>
            </div>

            <div>
                <div class="title_Benchmark" class="text-center">
                        <ul>
                            <li id="title_main" class="text-center col-1">　</li>
                            <li id="title_main" class="text-center col-4">제목</li>
                            <li id="title_name" class="text-center col-2">작성자</li>
                            <li class="title_td text-center col-1">댓글</li>
                            <li class="title_td text-center col-2">작성일</li>
                            <li class="title_td text-center col-1">좋아요</li>
                        </ul>
                </div>

                <div>
                    <div>
                        <? foreach ($result as $board) { ?>
                            <? if ($board->main_status == 2 && $board->board_status == 1) { ?>
                                <div id="main_status_move">
                                    <ul>
                                        <li id="main_status" class="text-center">공지</li>
                                        <li id="main_title" class="text-center col-5">
                                            <a
                                                href="/board/board_detail?category=<?= $board->category_num ?>&board_num=<?= $board->article_num ?>"><?= $board->title ?></a>
                                        </li>
                                        <li id="main_name" class="text-center col-1">
                                            <?= $board->user_id ?>
                                        </li>
                                        <li id="main_common" class="text-center col-2">
                                            <?= $board->comment_count ?>
                                        </li>
                                        <li id="main_te" class="text-center col-1">
                                            <?= date('Y-m-d', strtotime($board->write_date)) ?>
                                        </li>
                                        <li id="main_heart" class="text-center col-2">
                                            <?= $board->heart_count ?>
                                        </li>
                                    </ul>
                                </div>
                            <? } ?>

                            <ul class="board_list">
                                <li id="board_num" class="text-center col-1">
                                    <?= $board->article_num ?>
                                </li>
                                <li id="board_write_title" class="text-center col-4"><a
                                        href="/board/board_detail?category=<?= $board->category_num ?>&board_num=<?= $board->article_num ?>">
                                        <?= $board->title ?>
                                    </a>
                                    <? if (!$board->file_path == "") { ?>
                                        <img src="/assets/images/fileimg.png" width="20px" height="20px">
                                    <? } ?>
                                    <? if (strpos($board->content, "<img")) { ?>
                                        <img src="/assets/images/img.png" width="20px" height="20px">
                                    <? } ?>
                                    <? if ($board->comment_count != 0) { ?>
                                        <span class="title_right_color">[<?= $board->comment_count ?>]</span>
                                    <? } ?>
                                </li>
                                <li id="board_write_name" class="text-center col-2">
                                    <?= $board->user_id ?>
                                </li>
                                <li id="board_comment" class="text-center col-1">
                                    <?= $board->comment_count ?>
                                </li>
                                <li id="board_date" class="text-center col-2">
                                    <?= date('Y-m-d', strtotime($board->write_date)) ?>
                                </li>
                                <li id="board_heart" class="text-center col-1">
                                    <?= $board->heart_count ?>
                                </li>
                            </ul>

                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
    <? } ?>

    <? if ($ct_num == 2) { ?>
        <!-- 전체글보기 카테고리 클릭했을경우 -->
        <div class="board_main">
            <div class="board_title">
                <h5>전체 글보기</h5>
            </div>

            <div>
                <div class="title_Benchmark" class="text-center">
                        <ul>
                            <li id="title_main" class="text-center col-1">　</li>
                            <li id="title_main" class="text-center col-4">제목</li>
                            <li id="title_name" class="text-center col-2">작성자</li>
                            <li class="title_td text-center col-1">댓글</li>
                            <li class="title_td text-center col-2">작성일</li>
                            <li class="title_td text-center col-1">좋아요</li>
                        </ul>
                </div>

                <div>
                        <? foreach ($all_gongji_board as $gong) { ?>
                            <? if ($gong->main_status == 2 && $gong->board_status == 1) { ?>
                                <div id="main_status_move">
                                    <ul>
                                        <li id="main_status" class="text-center col-1">공지</li>
                                        <li id="main_title" class="text-center col-5">
                                            <a
                                                href="/board/board_detail?category=<?= $gong->category_num ?>&board_num=<?= $gong->article_num ?>"><?= $gong->title ?></a>
                                        </li>
                                        <li id="main_name" class="text-center col-1">
                                            <?= $gong->user_id ?>
                                        </li>
                                        <li id="main_common" class="text-center col-2">
                                            <?= $gong->comment_count ?>
                                        </li>
                                        <li id="main_te" class="text-center col-1">
                                            <?= date('Y-m-d', strtotime($gong->write_date)) ?>
                                        </li>
                                        <li id="main_heart" class="text-center col-2">
                                            <?= $gong->heart_count ?>
                                        </li>
                                    </ul>
                                </div>
                            <? } ?>
                        <? } ?>
                        <? foreach ($result as $board) { ?>
                            <? if ($board->board_status == 1) { ?>
                                <ul class="board_list">
                                    <li id="board_num" class="text-center col-1">
                                        <?= $board->article_num ?>
                                    </li>
                                    <li id="board_write_title" class="text-center col-4"><a
                                            href="/board/board_detail?category=<?= $board->category_num ?>&board_num=<?= $board->article_num ?>">
                                            <?= $board->title ?>
                                        </a>
                                        <? if (!$board->file_path == "") { ?>
                                            <img src="/assets/images/fileimg.png" width="20px" height="20px">
                                        <? } ?>
                                        <? if (strpos($board->content, "<img")) { ?>
                                            <img src="/assets/images/img.png" width="20px" height="20px">
                                        <? } ?>
                                        <? if ($board->comment_count != 0) { ?>
                                            <span class="title_right_color">[<?= $board->comment_count ?>] </span>
                                        <? } ?>
                                    </li>
                                    <li id="board_write_name" class="text-center col-2">
                                        <?= $board->user_id ?>
                                    </li>
                                    <li id="board_comment" class="text-center col-1">
                                        <?= $board->comment_count ?>
                                    </li>
                                    <li id="board_date" class="text-center col-2">
                                        <?= date('Y-m-d', strtotime($board->write_date)) ?>
                                    </li>
                                    <li id="board_heart" class="text-center col-1">
                                        <?= $board->heart_count ?>
                                    </li>
                                </ul>
                            <? } ?>
                        <? } ?>
                </div>
            </div>
        </div>
        <div id="footer_btn">
            <ul id="footer_option">
                <li>
                    <?= $pagination ?>
                </li>
                <li class="board_btn_right">
                    <a href="/board/board_write"><button type="button" class="btn btn-outline-secondary">글쓰기</button></a>
                </li>
            </ul>
        </div>

        <div id="main_board_search">
            <form id="main_footer_search_form" action="/layout/full_board_list_search" method="get">
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

                <input type="text" name="board_footer_search" id="main_footer_go">
                <button type="submit">검색</button>
            </form>
        </div>
    <? } ?>
</body>