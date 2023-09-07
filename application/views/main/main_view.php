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
                        <? foreach ($result as $board) { ?>
                            <? if ($board->main_status == 2 && $board->board_status == 1) { ?>
                                <div id="main_status_move">
                                    <ul>
                                        <li id="main_status">공지</li>
                                        <li id="main_title">
                                            <a
                                                href="/board/board_detail?category=<?= $board->category_num ?>&board_num=<?= $board->article_num ?>"><?= $board->title ?></a>
                                        </li>
                                        <li id="main_name">
                                            <?= $board->user_id ?>
                                        </li>
                                        <li id="main_common">
                                            <?= $board->comment_count ?>
                                        </li>
                                        <li id="main_te">
                                            <?= date('Y-m-d', strtotime($board->write_date)) ?>
                                        </li>
                                        <li id="main_heart">
                                            <?= $board->heart_count ?>
                                        </li>
                                    </ul>
                                </div>
                            <? } ?>

                            <tr class="board_list">
                                <th id="board_num">
                                    <?= $board->article_num ?>
                                </th>
                                <th id="board_write_title"><a
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
                                </th>
                                <th id="board_write_name">
                                    <?= $board->user_id ?>
                                </th>
                                <th id="board_comment">
                                    <?= $board->comment_count ?>
                                </th>
                                <th id="board_date">
                                    <?= date('Y-m-d', strtotime($board->write_date)) ?>
                                </th>
                                <th id="board_heart">
                                    <?= $board->heart_count ?>
                                </th>
                            </tr>

                        <? } ?>
                    </table>
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
                        <? foreach ($all_gongji_board as $gong) { ?>
                            <? if ($gong->main_status == 2 && $gong->board_status == 1) { ?>
                                <div id="main_status_move">
                                    <ul>
                                        <li id="main_status">공지</li>
                                        <li id="main_title">
                                            <a
                                                href="/board/board_detail?category=<?= $gong->category_num ?>&board_num=<?= $gong->article_num ?>"><?= $gong->title ?></a>
                                        </li>
                                        <li id="main_name">
                                            <?= $gong->user_id ?>
                                        </li>
                                        <li id="main_common">
                                            <?= $gong->comment_count ?>
                                        </li>
                                        <li id="main_te">
                                            <?= date('Y-m-d', strtotime($gong->write_date)) ?>
                                        </li>
                                        <li id="main_heart">
                                            <?= $gong->heart_count ?>
                                        </li>
                                    </ul>
                                </div>
                            <? } ?>
                        <? } ?>
                        <? foreach ($result as $board) { ?>
                            <? if ($board->board_status == 1) { ?>
                                <tr class="board_list">
                                    <th id="board_num">
                                        <?= $board->article_num ?>
                                    </th>
                                    <th id="board_write_title"><a
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
                                    </th>
                                    <th id="board_write_name">
                                        <?= $board->user_id ?>
                                    </th>
                                    <th id="board_comment">
                                        <?= $board->comment_count ?>
                                    </th>
                                    <th id="board_date">
                                        <?= date('Y-m-d', strtotime($board->write_date)) ?>
                                    </th>
                                    <th id="board_heart">
                                        <?= $board->heart_count ?>
                                    </th>
                                </tr>
                            <? } ?>
                        <? } ?>
                    </table>
                </div>
            </div>
        </div>
        <div id="footer_btn">
            <ul id="footer_option">
                <li>
                    <?= $pagination ?>
                </li>
                <li class="board_btn_right">
                <?if(isset($id)){?>
                    <a href="/board/board_write"><button type="button" class="btn btn-outline-secondary">글쓰기</button></a>
                <?}?>
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