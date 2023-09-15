<head></head>
<meta charset="UTF-8">
<title></title>
<link rel="stylesheet" href="/assets/css/board/board_detail.css">
<script src="/assets/js/board/board_detail.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>
    <div id="datail_body">
        <ul id="detail_header_menu">
            <li>
                <div id="detail_category_title">
                    <a href="/board/board_list?name=<?= $category->category_name ?>&num=<?= $category->category_num ?>"
                        id="category_move_detail">
                        <p>
                            <?= $category->category_name ?> >
                        </p>
                    </a>
                </div>
            </li>

            <? if (isset($user_id)) { ?>

                <li class="detail_header_menu_btn" id="next">
                    <a
                        href="/board/board_detail/next_post?category=<?= $category->category_num ?>&board_num=<?= $board->article_num ?>"><button>다음글</button></a>
                </li>

                <li class="detail_header_menu_btn" id="privious">
                    <a
                        href="/board/board_detail/previous_post?category=<?= $category->category_num ?>&board_num=<?= $board->article_num ?>"><button>이전글</button></a>
                </li>
            <? } ?>
        </ul>
        <div id="detail_header_one">
            <ul>
                <? if ($board->main_status == 2) { ?>
                    <li id="detail_gongji">공지</li>
                <? } ?>
                <li id="detail_title">
                    <h5>
                        <?= $board->title ?>
                    </h5>
                </li>
            </ul>
        </div>
        <input type="hidden" id="board_detail_category_num" value="<?= $category->category_num ?>">
        <div id="detail_write_profile">
            <div id="picture_info">
                <? if ($user_info->image_path != "") { ?>
                    <img id="picture" src="<?= $user_info->image_path ?>" width="50" height="50">
                <? } else { ?>
                    <img id="picture" src="/assets/images/profile.png" width="40" height="40">
                <? } ?>
                <ul>
                    <li>
                        <?= $board->user_id ?>
                    </li>
                    <li>
                        <?= date('Y-m-d H:i', strtotime($board->write_date)) ?>
                    </li>
                </ul>
                <div id="detail_option">
                    <ul>
                        <li class="option_detween">댓글 :
                            <?= $comments_num ?>
                        </li>
                        <? if ($id_authority == 2) { ?>
                            <? if ($board->main_status == 1) { ?>
                                <li class="option_detween"><button type="button" class="btn btn-secondary"
                                        id="gongji_btn">공지변경</button></li>
                            <? } ?>
                            <? if ($board->main_status == 2) { ?>
                                <li class="option_detween"><button type="button" class="btn btn-secondary"
                                        id="gongji_btn">공지취소</button></li>
                            <? } ?>
                        <? } ?>
                    </ul>
                    <input type="hidden" id="detail_article_num" value="<?= $board->article_num ?>">
                </div>
            </div>

        </div>
        <hr>

        <!-- content부분 -->
        <div id="detail_content">
            <? if ($file_info != null) { ?>
                <div class="content_file">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            첨부파일
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <? foreach ($file_info as $file) { ?>
                                <li>
                                    <a class="dropdown-item active" href="<?= $file->file_path ?>"
                                        download="<?= $file->file_name ?>">
                                        <?= $file->file_name ?>
                                    </a>
                                </li>
                            <? } ?>
                        </ul>
                    </div>
                </div>
            <? } ?>
            <?= $board->content ?>
        </div>
        <div id="detail_heart">
            <ul>

                <li>
                    <a href="#" class="heart_up" data-article="<?= $board->article_num ?>">
                        <? if ($heart_check == false) { ?>
                            <img src="/assets/images/null_heart.png" width="25" height="25">
                        <? } else { ?>
                            <img src="/assets/images/full_heart.png" width="25" height="25">
                        <? } ?>
                    </a>좋아요<span class="heart_count">
                        <?= $heart_num ?>
                    </span>
                </li>
                <li>
                    <img src="/assets/images/comment_img.png" width="25" height="25">
                    <span>댓글
                        <?= $comments_num ?>
                    </span>
                </li>
            </ul>
        </div>
        <hr>
        <!-- 댓글부분 -->
        <div id="detail_comment">
            <ul>
                <li id="detail_comment_title">
                    <h5>댓글</h5>
                </li>
                <li id="detail_comment_btn_1"><a href="#" onclick="comments_reupdate('등록순',event)">등록순</a></li>
                <li>|</li>
                <li id="detail_comment_btn_2"><a href="#" onclick="comments_reupdate('최신순',event)">최신순</a></li>
            </ul>
            <!-- 댓글 단사람 프로필과 내용 -->
            <div class="detail_comments_member" id="asdasd">
                <? foreach ($comments as $comment) { ?>
                    <? $depth = $comment->depth;
                    $margin_left = $depth * 25;
                    ?>
                    <div class="comment-container" style="margin-left:<?= $margin_left ?>px;">
                        <? if ($comment->image_path != "") { ?>
                            <img id="picture" src="<?= $comment->image_path ?>" width="40" height="40">
                        <? } else { ?>
                            <img id="picture" src="/assets/images/profile.png" width="40" height="40">
                        <? } ?>
                        <ul>
                            <li>
                                <?= $comment->user_id ?>
                            </li>
                            <li id="detail_comment_write_name">
                                <? if ($board->user_id == $comment->user_id) { ?>
                                    <span>　작성자　</span>
                                <? } else { ?>
                                <? } ?>
                            </li>
                            <p>
                                <?= $comment->content ?>
                                <? if ($comment->img_path != "") { ?>
                                    <img src="<?= $comment->img_path ?>" width="75" height="75">
                                <? } else { ?>

                                <? } ?>
                            </p>
                            <ul>
                                <li>
                                    <?= date('Y-m-d H:i', strtotime($comment->write_date)) ?>
                                </li>
                                <? if (isset($user_id)) { ?>
                                    <li><a href="" class="toggle-recomment_form"
                                            onclick="toggle_recomment_btn(event,<?= $comment->comment_num ?>)">답글 쓰기</a></li>
                                <? } ?>
                                <? if ($id_authority == 2 || $comment->user_id == $user_id) { ?>
                                    <li id="detail_comments_sub_btn1"><button class="btn btn-secondary edit-comment-btn"
                                            onclick="toggleEditSection(<?= $comment->comment_num ?>)">댓글수정</button></li>
                                    <li id="detail_comments_sub_btn2"><button
                                            class="btn btn-secondary detail_comments_sub_btn2">댓글삭제</button></li>
                                <? } ?>
                                <input type="hidden" class="comment_num" value="<?= $comment->comment_num ?>">
                            </ul>
                        </ul>
                    </div>
                    <!-- 댓글 수정 -->
                    <div id="edit-comment-section-<?= $comment->comment_num ?>" class="edit-comment-section">
                        <form id="detail_comment_modify_form-<?= $comment->comment_num ?>"
                            action="/board/board_detail/board_comment_update" method="post">
                            <div>
                                <input type="hidden" id="category_num<?= $comment->comment_num ?>" name="category_num"
                                    value="<?= $category->category_num ?>">
                                <input type="hidden" id="article_num<?= $comment->comment_num ?>" name="article_number"
                                    value="<?= $board->article_num ?>">
                                <input type="hidden" id="detail_comment_num<?= $comment->comment_num ?>"
                                    name="detail_comment_num" value="<?= $comment->comment_num ?>">
                                <div id="modify_box">
                                    <!-- <span class="edit_comment_id">
                                        <?= $comment->user_id ?>
                                    </span> -->
                                    <input type="text" id="detail_comment_content<?= $comment->comment_num ?>"
                                        name="detail_comment_content" class="detail_comment_content"
                                        placeholder="<?= $comment->content ?>">
                                    <button type="button" class="btn btn-secondary edit_btn"
                                        onclick="comment_update(<?= $comment->comment_num ?>)">수정</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- 답글다는부분 -->
                    <div id="edit-recomment-section-<?= $comment->comment_num ?>" class="edit-recomment-section">
                        <form id="detail_recomment_write_form" action="/board/board_detail/board_recomment_writes"
                            method="post">
                            <div>
                                <input type="hidden" name="category_num" id="ctct1" value="<?= $category->category_num ?>">
                                <input type="hidden" name="article_number" value="<?= $board->article_num ?>">
                                <input type="hidden" name="detail_comment_num" value="<?= $comment->comment_num ?>">
                                <input type="hidden" name="recomment_parentID"
                                    value="<?= $comment->parent_id == 0 ? '0' : $comment->parent_id ?>">

                                <h5 class="edit_recomment_id">
                                    <?= $user_id ?>
                                </h5>
                                <input type="text" name="detail_recomment_content" class="detail_recomment_content"
                                    placeholder="답글">
                                <button type="submit" class="btn btn-secondary reedit_btn">등록</button>
                            </div>
                        </form>
                    </div>
                <? } ?>
                <? if (isset($user_id)) { ?>
                    <!-- 게시글 댓글 다는 부분 -->
                    <form id="detail_comments_write" action="/board/board_detail/add_comment" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" id="comment_user_id" name="user_id" value="<?= $user_id ?>">
                        <input type="hidden" id="comment_article_num" name="article_num" value="<?= $board->article_num ?>">
                        <input type="hidden" id="comment_post_parent_id" name="parent_Id" value="<?= $board->parent_id ?>">
                        <div id="detail_comments_write_name">
                            <h5>
                                <span id="comment_name">
                                    <?= $user_id ?>
                                </span>
                            </h5>
                        </div>
                        <div class="comment_box">
                            <ul>
                                <li><span id="detail_previewImage"></span></li>
                                <li><input id="detail_comments_write_text" type="text" name="detail_comments_write_text"
                                        placeholder="댓글을 남겨보세요."></li>
                                <ul class="detail_comment_btn_right">
                                    <li><label for="detail_comments_file" class="comments_poto">
                                            <img src="/assets/images/comment_camera.png" alt="alt" width="25" height="25">
                                        </label>
                                        <input type="file" name="detail_comment_file" id="detail_comments_file"
                                            accept=".jpg, .jpeg, .png">
                                    </li>
                                    <li><button type="submit" class="btn btn-secondary"
                                            id="detail_comments_write_text_submit">등록</button></li>
                                </ul>
                            </ul>

                        </div>
                </div>
                </form>
            <? } else { ?>
                <form id="detail_comments_write" action="#" method="post">
                    <div class="comment_boxing">
                        <span id="none_login">로그인후 이용해주세요</span>
                    </div>
                </form>
            <? } ?>
        </div>
    </div>
    </div>


    <!-- 하단 버튼들 -->
    <? if (isset($user_id)) { ?>
        <div id="detail_footer_btn_group">
            <ul>
                <li><a href="/board/board_write"><button type="button" class="btn btn-secondary">글쓰기</button></a></li>
                <li><button type="button" class="btn btn-secondary reply_board">답글</button></li>
                <? if ($user_id == $board->user_id || $id_authority == 2) { ?>
                    <li><button type="button" id="modify_category" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop_k">이동</button></li>
                    <li><a
                            href="/board/board_detail/detail_delete?board=<?= $board->article_num ?>&category=<?= $category->category_num ?>"><button
                                type="button" class="btn btn-secondary">삭제</button></a></li>
                    <li><a href="/board/board_write/modify?board_num=<?= $board->article_num ?>"><button type="button"
                                class="btn btn-secondary">수정</button></a></li>
                <? } ?>
                <li class="detail_footer_btn_right"><a
                        href="/board/board_list?num=<?= $category->category_num ?>&name=<?= $category->category_name ?>"><button
                            type="button" class="btn btn-secondary">목록</button></a>
                    <a href="#"><button type="button" class="btn btn-secondary">▲TOP</button></a>
                </li>
            </ul>
        </div>
    <? } ?>

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
                    <form>
                        <input type="hidden" id="modify_article_num" name="modify_article_num"
                            value="<?= $board->article_num ?>">
                        <select id="modify_list">
                            <? foreach ($category_modify as $modify_list) { ?>
                                <? if ($modify_list->category_num != 0) { ?>
                                    <option value="<?= $modify_list->category_num ?>"><?= $modify_list->category_name ?>
                                    </option>
                                <? } ?>
                            <? } ?>
                        </select>
                        <div id="delete_btn">
                            <button type="button" class="btn btn-secondary" id="category_modify">이동</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <? if (isset($user_id)) { ?>
        <div id="semi_list_list">
            <div id="semi_title">
                <?= $category->category_name ?> 게시판글
            </div>
            <? foreach ($mini_list as $semi_list) { ?>
                <div id="semi_list">
                    <? if ($semi_list->board_status == 1 && $semi_list->category_num == $category->category_num) { ?>
                        <ul id="why">
                            <li id="semi_first"><a
                                    href="/board/board_detail?category=<?= $semi_list->category_num ?>&board_num=<?= $semi_list->article_num ?>">
                                    <?= $semi_list->title ?>
                                </a></li>
                            <li id="semi_middle">
                                <?= $semi_list->user_id ?>
                            </li>
                            <li id="semi_last">
                                <?= date('Y-m-d', strtotime($semi_list->write_date)) ?>
                            </li>
                        </ul>
                    </div>
                <? } ?>
            <? } ?>
        </div>
    <? } ?>
</body>