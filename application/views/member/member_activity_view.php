<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="/assets/css/member/member_activity.css">
    <script src="/assets/js/member/member_activity.js"></script>
</head>

<body>
    <div id="member_activity_body">
        <div id="activity_write_board">
            <div id="member_activity_profile">
                <ul>
                    <li>
                        <div id="member_profile">
                            <? if ($member->image_path != "") { ?>
                                <img src="<?= $member->image_path ?>" width="75" height="75">
                            <? } else { ?>
                                <img src="/assets/images/profile.png" width="75" height="75" rel="img">
                            <? } ?>
                        </div>
                    </li>
                    <li id="member_profile_info">
                        <span>
                            <?= $member->user_nickname ?> [ <?= $member->user_id ?> ]
                        </span><br>
                        <span>작성글
                            <?= $member->board_num ?> 개
                        </span>
                    </li>
                </ul>
            </div>
            <div id="member_activity_btn">
                <div>
                    <ul>
                        <li class="member_activity_btn_group1"><button class="activity_btn" id="activity_btn1"
                                onclick="show_div('activity_write_mini_board')">작성글</button></li>
                        <li class="member_activity_btn_group2"><button class="activity_btn" id="activity_btn2"
                                onclick="show_div('activity_write_comment')">작성한 댓글</button></li>
                        <li class="member_activity_btn_group3"><button class="activity_btn" id="activity_btn3"
                                onclick="show_div('activity_delete_board')">삭제한 게시글</button></li>
                    </ul>
                </div>
                <hr>
                <!-- 작성글 -->
                <div id="activity_write_mini_board">
                    <div>
                        <ul>
                            <li class="member_activity_criteria1">제목</li>
                            <li class="member_activity_criteria2">작성일</li>
                            <li class="member_activity_criteria3">댓글</li>
                            <li class="member_activity_criteria4">좋아요</li>
                        </ul>
                    </div>
                    <hr>
                    <form action="/member/member_activity/selected_delete_board" method="post">
                        <? foreach ($act_board as $board) { ?>
                            <? if ($board->board_status == 1) { ?>
                                <div>
                                    <ul>
                                        <li class="member_activity_number"><input type="checkbox" class="checkbox_board"
                                                name="member_activity_checkbox[]" value="<?= $board->article_num ?>">
                                        </li>
                                        <li class="member_activity_title">
                                            <a
                                                href="/board/board_detail?category=<?= $board->category_num ?>&board_num=<?= $board->article_num ?>">
                                                <?= $board->title ?>
                                            </a>
                                            <? if ($board->file_path) { ?>
                                                <span><img src="/assets/images/fileimg.png" width="25" height="25" rel="img"></span>
                                            <? } ?>
                                            <? if (strpos($board->content, "<img")) { ?>
                                                <span><img src="/assets/images/img.png" width="25" height="25"></span>
                                            <? } ?>
                                            <span id="comment_count">[<?= $board->comment_count ?>]
                                            </span>
                                        </li>
                                        <li class="member_activity_date">
                                            <?= date('Y-m-d', strtotime($board->write_date)) ?>
                                        </li>
                                        <li class="member_activity_comments">
                                            <?= $board->comment_count ?>
                                        </li>
                                        <li class="member_activity_heart">
                                            <?= $board->heart_count ?>
                                        </li>
                                    </ul>
                                    <hr>
                                </div>
                            <? } ?>
                        <? } ?>
                        <div>
                            <ul>
                                <li><input type="checkbox" class="check_all" name="check_all"
                                        onclick="selectAllboard(this)"> 전체선택</li>
                                <li><button type="submit" class="btn btn-secondary footer_btn">삭제</button></li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
            <!-- 작성한댓글 -->
            <div id="activity_write_comment">
                <div>
                    <ul>
                        <li class="member_activity_criteria_comment1">댓글</li>
                        <li class="member_activity_criteria_comment2">게시글</li>
                        <li class="member_activity_criteria_comment3">등록일</li>
                    </ul>
                </div>
                <hr>
                <form action="/member/member_activity/selected_delete_comment" method="post">
                    <? foreach ($act_comments as $comment) { ?>
                        <div>
                            <ul>
                                <li class="member_activity_number"><input type="checkbox" class="checkbox_comments"
                                        name="member_activity_checkbox[]" value="<?= $comment->comment_num ?>"></li>
                                <li class="member_activity_title">
                                    <?= $comment->content ?>
                                </li>
                                <li class="member_activity_board_comment"><a
                                        href="/board/board_detail?category=<?= $comment->board_category_num ?>&board_num=<?= $comment->article_num ?>">
                                        <?= $comment->board_title ?>
                                    </a></li>
                                <li class="member_activity_date_comment">
                                    <?= date('Y-m-d', strtotime($comment->write_date)) ?>
                                </li>
                            </ul>
                            <hr>
                        </div>
                    <? } ?>
                    <div>
                        <ul>
                            <li><input type="checkbox" name="delte_all" class="check_all"
                                    onclick="selectAllComment(this)"> 전체선택</li>
                            <li><button type="submit" class="btn btn-secondary footer_btn">삭제</button></li>
                        </ul>
                    </div>
                </form>
            </div>


            <!-- 삭제한게시글 -->
            <div id="activity_delete_board">
                <div>
                    <ul>
                        <li class="member_activity_delete_criteria1">제목</li>
                        <li class="member_activity_delete_criteria2">삭제일</li>
                    </ul>
                </div>
                <hr>
                <form action="/member/member_activity/board_restore" method="post">
                    <? foreach ($act_board as $board) { ?>
                        <? if ($board->board_status == 2) { ?>
                            <div>
                                <ul>
                                    <li class="member_activity_number"><input type="checkbox" class="checkbox_delete"
                                            name="member_activity_checkbox[]" value="<?= $board->article_num ?>"></li>
                                    <li class="member_activity_title">
                                        <?= $board->title ?>
                                    </li>
                                    <li class="member_activity_delete_date">
                                        <?= date('Y-m-d', strtotime($board->delete_date)) ?>
                                    </li>
                                </ul>
                            </div>
                            <hr>
                        <? } ?>
                    <? } ?>
                    <div>
                        <ul>
                            <li><input type="checkbox" name="delte_all" class="check_all"
                                    onclick="selectAlldelete(this)"> 전체선택</li>
                            <li><button type="submit" class="btn btn-secondary footer_btn">복구</button></li>
                        </ul>
                    </div>
                </form>
            </div>


        </div>
    </div>
</body>

</html>