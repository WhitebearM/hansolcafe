<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>한솔게시판</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/assets/css/common/layout.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/assets/js/common/common.js"></script>
</head>
<body>
    <!-- 헤더부분 -->
    <div id="header">
        <div id="logo">
            <a href="/layout"><img src="/assets/images/logolo.png" width="75px" height="75px"></a>
        </div>
        <? if (isset($id)) { ?>
            <div class="member_btn">
                <ul class="subtitlee">
                    <li>
                        <a><button type="button" class="btn btn btn-success layout_main_member_btn" data-bs-toggle="modal"
                                data-bs-target="#member_modify">정보수정</button></a>
                    </li>
                    <li><a href="/login/login/logout_member"><button type="button"
                                class="btn btn btn-danger layout_main_member_btn">로그아웃</button></a></li>
                </ul>
            </div>
        <? } else { ?>
            <div class="member_btn">
                <ul class="subtitlee">
                    <li><a href="/member/memberform"><button type="button"
                                class="btn btn btn-success layout_main_member_btn">회원가입</button></a>
                    </li>
                    <li><a href="/login/login"><button type="button"
                                class="btn btn btn-danger layout_main_member_btn">로그인</button></a></li>
                </ul>
                </a>
            </div>
        <? } ?>

        <!-- 회원정보 수정전 모달창 -->
        <div class="modal fade" id="member_modify" data-bs-backdrop="static" tabindex="-1"
            aria-labelledby="member_modifyLabel" aria-hidden="true">
            <div class="modal-dialog" id="center">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">로그인정보 확인</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="member_modify_confirm_form" action="/layout/ck_modify_member" method="post">
                            <input type="text" name="member_modify_confirm_id" id="member_modify_confirm_id"
                                placeholder="아이디"><br>
                            <input type="password" name="member_modify_confirm_pw" id="member_modify_confirm_pw"
                                placeholder="비밀번호"><br>
                            <button type="submit" class="btn btn-secondary" id="submitmember_info">확인</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <input type="password" id="inputPassword" placeholder="비밀번호 입력">
                <button id="checkPasswordBtn">확인</button>
                <div id="modalMessage"></div>
            </div>
        </div>


        <!-- 배너부분 -->
        <div id="banner">
            <a href="/layout"><img src="/assets/images/banner.jpg" width="1080x" height="300px"></a>
        </div>
        <!-- 검색창 -->
        <div id="hdsearch">
            <form action="/layout/board_search" method="get" id="header_search_form">
                <?if(!isset($search_title)){?>
                    <input id="sh_text" type="text" name="hd_search">
                <?}else if($search_title != ""){?>
                    <input id="sh_text" type="text" name="hd_search" value="<?=$search_title?>">
                <?}?>
                <button class="sh_btn" type="submit">검색</button>
            </form>
        </div>
    </div>

    <!-- 사이드부분 -->
    <div class="member_side_article">
        <div id="article">
            <ul class="member_btn_cafebtn">
                <li>
                    <div class="op1">
                        <button id="article_btn_1" class="btn_cafe_info" type="button"
                            onclick="info_move('cafe')">카페정보</button>
                    </div>
                </li>
                <li>
                    <div>
                        |
                    </div>
                </li>
                <li>
                    <div class="op1">
                        <button id="article_btn_2" class="btn_cafe_info" type="button" onclick="info_move('member')"
                            onclick="member_login_info($id)">나의활동</button>
                    </div>
                </li>
            </ul>
            <div id="cafe_info">
                <div id="profile">
                    <a><img src="<?= $result->image_path ?>" width="70px" height="70px"></a>
                </div>
                <div id="profile_info">
                    <ul class="member_btn_cafebtn">
                        <li>
                            <img src="/assets/images/manager.png" width="10px" height="10px"><span
                                class="ft_size">매니저_</span>
                            <?= $result->user_nickname ?>
                        </li>
                        <li>
                            <span class="ft_size">since 2023.08.07</span>
                        </li>
                        <li>
                            <span class="ft_size">회원수
                                <?= $count->total_member ?>명
                            </span>
                        </li>
                        <li>
                            <span class="ft_size">개발 테스트 카페</span>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- 나의활동 정보부분 -->
            <? if (isset($member) && isset($count)) { ?>
                <div id="member_info">
                    <div id="profile">
                        <? if ($member->image_path != "") { ?>
                            <a><img src="<?= $member->image_path ?>" width="70px" height="70px"></a>
                        <? } else { ?>
                            <a><img src="/assets/images/profile.png" width="70px" height="70px"></a>
                        <? } ?>
                    </div>
                    <div id="profile_info">
                        <ul>
                            <li>
                                <span><img src="/assets/images/member_modify_icon.png" rel="게시글 설정" width="10" height="10"></span>
                                <span class="ft_size">
                                    <a href="/member/member_activity">
                                        <?= $member->user_nickname ?> 님
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span class="ft_size">작성한 게시글 :
                                    <a href="/member/member_activity">
                                        <?= $board_count->total_board ?>개
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span class="ft_size">작성한 댓글
                                    <a href="/member/member_activity">
                                        <?= $comment_count->total_comments ?>개
                                    </a>
                                </span>
                            </li>
                            <li>
                                <span class="ft_size">방문횟수
                                    <?= $member->count ?>회
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            <? } else {
            } ?>

            <a href="#" onclick="member_login_info()">
                <div id="main_write">
                    <p>카페 글쓰기</p>
                </div>
            </a>

            <? if ($authority == 2) { ?>
                <div class="btn-group">
                    <span><img alt="가위" src="/assets/images/cut.png" width="15" height="15">카테고리 추가/제거</span>
                    <button type="button" class="ct_plus" data-bs-toggle="modal"
                        data-bs-target="#addCategoryModal">+</button>
                    <button type="button" class="ct_minus" id="delete_category" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop">-</button>
                </div>
            <? }else{ ?>
            <?}?>

            <!-- foreach문돌리기 -->
            <div id="category_list">
                <div id="all_list">
                    <img alt="카테고리이미지" src="/assets/images/category_img.png" width="15" height="15"><a
                        href="/layout/full_board_list">전체글보기</a>
                </div>
                <ul id="category_move">
                    <? foreach ($category_list as $category) { ?>
                        <? if ($category->category_num != 0) { ?>
                            <li id="category_list_style_none">
                                <img alt="카테고리이미지" src="/assets/images/category_img.png" width="15" height="15"><a
                                    href="/board/board_list?name=<?= $category->category_name ?>&num=<?= $category->category_num ?>">
                                    <?= $category->category_name ?>
                                </a>
                            </li>
                        <? } ?>
                    <? } ?>
                </ul>
            </div>

            <div id="date_board">
                <span><img alt="최근게시글이미지" src="/assets/images/date_board.png" width="15" height="15"> 최근게시글</span>
                <? foreach ($date_board as $board) { ?>
                    <ul class="board_tttt">
                        <li><a
                                href="/board/board_detail?category=<?= $board->category_num ?>&board_num=<?= $board->article_num ?>"><?= $board->title ?></a></li>
                    </ul>
                <? } ?>
            </div>
            <? if (isset($id)) { ?>
                <div class="member_delete_box">
                    <span class="member_delete"><a href="/member/member_delete">회원탈퇴</a></span>
                </div>
            <? } ?>
        </div>
    </div>

    <?if($authority == 2){?>
    <!-- 카테고리 추가 모달창 -->
    <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" id="center">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">카테고리 추가</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm" action="/layout/category_insert" method="post">
                        <input type="text" name="category_name" id="category_name" placeholder="카테고리 이름" required>
                        <div id="category_error"></div>
                        <button type="button" class="btn btn-secondary" id="submitCategory" disabled>추가</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?}?>

    <?if($authority == 2){?>
    <!-- 카테고리 제거 모달창 -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">카테고리 제거</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/layout/del_cate" method="post">
                        <select name="del_list" id="del_list">
                            <? foreach ($category_list as $delete_list) { ?>
                                <? if ($delete_list->category_num != 0) { ?>
                                    <option value="<?= $delete_list->category_num ?>"><?= $delete_list->category_name ?>
                                    </option>
                                <? } ?>
                            <? } ?>
                        </select>
                        <div id="delete_btn">
                            <button type="submit" class="btn btn-secondary" id="delete">제거</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?}?>

    <!-- 콘텐츠부분 -->
    <div class="layout_content">
        <?= $content ?>
    </div>
    <!-- 푸터부분 -->
    <footer id="footer">
        <a id="footer_move" href="/layout/full_board_list">Hansol CAFE</a>
    </footer>

</body>

</html>