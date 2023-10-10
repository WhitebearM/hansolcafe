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
            <div class="main_real_title">
                <ul>
                    <li>
                        <div>
                          <h6>전체 글보기</h6>
                        </div>
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
                            <? foreach ($gongji as $board) { ?>
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
                                        <li id="main_te" class="text-center">
                                            <?= date('Y-m-d', strtotime($board->write_date)) ?>
                                        </li>
                                        <li id="main_hearte" class="text-center">
                                            <?= $board->heart_count ?>
                                        </li>
                                    </ul>
                                </div>
                            <? } ?>
                            <?}?>
                            <? foreach ($result as $board) { ?>
                            <div class="main_board_list">
                                <ul class="board_list">
                                    <li id="board_num" class="text-center col-1">
                                        <?= $board->article_num ?>
                                    </li>
                                    <li id="board_write_title" class="col-4"><a
                                            href="/board/board_detail?category=<?= $board->category_num ?>&board_num=<?= $board->article_num ?>">
                                            <?= $board->title ?>
                                        </a>
                                        <span id="new_board">
                                                    <?= (strtotime('now') - strtotime($board->write_date)) / (60 * 60) < 12 ? "[new]" : ""; ?>
                                        </span>
                                        <? if (!$board->file_count == "") { ?>
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
                            </div>
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
                <h6>전체 글보기</h6>
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
                                        <li id="main_status" class="text-center">공지</li>
                                        <li id="main_title" class="text-center">
                                            <a
                                                href="/board/board_detail?category=<?= $gong->category_num ?>&board_num=<?= $gong->article_num ?>"><?= $gong->title ?></a>
                                        </li>
                                        <li id="main_name" class="text-center">
                                            <?= $gong->user_id ?>
                                        </li>
                                        <li id="main_common" class="text-center">
                                            <?= $gong->comment_count ?>
                                        </li>
                                        <li id="main_day">
                                            <?= date('Y-m-d', strtotime($gong->write_date)) ?>
                                        </li>
                                        <li id="main_heart" class="text-center">
                                            <?= $gong->heart_count ?>
                                        </li>
                                    </ul>
                                </div>
                            <? } ?>
                        <? } ?>
                        
                        <? foreach ($result as $board) { ?>
                            <? if ($board->board_status == 1) { ?>
                                <div class="main_board_list">
                                <ul class="board_list">
                                    <li id="board_num" class="text-center col-1">
                                        <?= $board->article_num ?>
                                    </li>
                                    <li id="board_write_title" class="col-4"><a
                                            href="/board/board_detail?category=<?= $board->category_num ?>&board_num=<?= $board->article_num ?>">
                                            <?= $board->title ?>
                                        </a>
                                        <span id="new_board">
                                                    <?= (strtotime('now') - strtotime($board->write_date)) / (60 * 60) < 12 ? "[new]" : ""; ?>
                                                </span>
                                        <? if (!$board->file_count != 0) { ?>
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
                                </div>
                            <? } ?>
                        <? } ?>
                </div>
            </div>
            <div id="footer_btn">
                <ul id="footer_option">
                    <li id="pagi_btn_right">
                        <span id="main_pagination"><?= $pagination ?></span>
                    </li>
                    <li class="board_btn_right">
                        <a href="/board/board_write"><button type="button" class="btn btn btn-danger">글쓰기</button></a>
                    </li>
                </ul>
            </div>
            <div id="main_board_search">
                <form id="main_footer_search_form" action="/layout/full_board_list_search" method="get">
                    <?if(!isset($day)){?>
                    <select name="category_option_1">
                        <option value="all">전체</option>
                        <option value="1_day">1일</option>
                        <option value="1_week">일주일</option>
                        <option value="1_months">1개월</option>
                        <option value="6_months">6개월</option>
                        <option value="1_year">1년</option>
                    </select>
                    <?}else{?>
                    <select name="category_option_1">
                        <?if($day == "all"){?>
                            <option value="all" selected>전체</option>
                        <?}else{?>
                            <option value="all">전체</option>
                        <?}?>
                        <?if($day == "1_day"){?>
                            <option value="1_day" selected>1일</option>
                        <?}else{?>
                            <option value="1_day">1일</option>
                        <?}?>
                        <?if($day == "1_week"){?>
                            <option value="1_week" selected>일주일</option>
                        <?}else{?>
                            <option value="1_week">일주일</option>
                        <?}?>
                        <?if($day == "1_months"){?>
                            <option value="1_months" selected>1개월</option>
                        <?}else{?>
                            <option value="1_months">1개월</option>
                        <?}?>
                        <?if($day == "6_months"){?>
                            <option value="6_months" selected>6개월</option>
                        <?}else{?>
                            <option value="6_months">6개월</option>
                        <?}?>
                        <?if($day == "1_year"){?>
                            <option value="1_year" selected>1년</option>
                        <?}else{?>
                            <option value="1_year">1년</option>
                        <?}?>
                    </select>
                    <?}?>
    
                    <?if(!isset($search_sel)){?>
                    <select name="category_option_2">
                        <option value="board_comment">게시글 + 댓글</option>
                        <option value="title">제목만</option>
                        <option value="board_writer">글작성자</option>
                        <option value="content">댓글내용</option>
                        <option value="comment_writer">댓글 작성자</option>
                    </select>
                    <?}else{?>
                    <select name="category_option_2">
                        <?if($search_sel == "board_comment"){?>
                            <option value="board_comment" selected>게시글 + 댓글</option>
                        <?}else{?>
                            <option value="board_comment">게시글 + 댓글</option>
                        <?}?>
                        <?if($search_sel == "title"){?>
                            <option value="title" selected>제목만</option>
                        <?}else{?>
                            <option value="title">제목만</option>
                        <?}?>
                        <?if($search_sel == "board_writer"){?>
                            <option value="board_writer" selected>글작성자</option>
                        <?}else{?>
                            <option value="board_writer">글작성자</option>
                        <?}?>
                        <?if($search_sel == "content"){?>
                            <option value="content" selected>댓글내용</option>
                        <?}else{?>
                            <option value="content">댓글내용</option>
                        <?}?>
                        <?if($search_sel == "comment_writer"){?>
                            <option value="comment_writer" selected>댓글 작성자</option>
                        <?}else{?>
                            <option value="comment_writer">댓글 작성자</option>
                        <?}?>
                    </select>
                    <?}?>
                    <?if(!isset($search_title_footer)){?>
                        <input type="text" name="board_footer_search" id="main_footer_go">
                    <?}else if($search_title_footer != ""){?>
                        <input type="text" name="board_footer_search" id="main_footer_go" value="<?=$search_title_footer?>">
                    <?}?>
                    <button class="custom-btn btn-16" type="submit">검색</button>
                 <!--    <span id="main_date_box">날짜로만 검색 : 
                    <input type="date" name="main_search_date" id="main_search_date"
                    <?if(isset($date)){?>
                    value="<?=$date?>"></span>
                    <?}?> -->
                </form>
            </div>
        </div>

    <? } ?>
</body>