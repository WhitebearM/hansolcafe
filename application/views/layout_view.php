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
            <a href="/layout"><img src="/assets/images/icon-cafe.png" width="75px" height="75px"></a>
        </div>
        <? if (isset($id)) { ?>
            <div class="memberform">
                <ul class="subtitle">
                    <li>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#member_modify">회원정보수정</button>
                    </li>
                    <li><a href="/login/login/logout_member"><button type="button"
                                class="btn btn-outline-secondary">로그아웃</button></a></li>
                </ul>
            </div>
        <? } else { ?>
            <div class="memberform">
                <ul class="subtitle">
                    <li><a href="/member/memberform"><button type="button"
                                class="btn btn-outline-secondary">회원가입</button></a>
                    </li>
                    <li><a href="/login/login"><button type="button" class="btn btn-outline-secondary">로그인</button></a></li>
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
        <a href="/layout">
            <div id="banner">
                <h3>한솔 카페에 오신걸 환경합니다.</h3>
            </div>
        </a>

        <div id="hdsearch">
            <form action="/layout/board_search" method="get" id="header_search_form">
                <input id="sh_text" type="text" name="hd_search">
                <button class="sh_btn" type="submit">검색</button>
            </form>
        </div>
    </div>
    <!-- 사이드부분 -->
    <div id="article">
        <ul class="subtitle">
            <li>
                <div style="margin-right:25px;">
                    <button class="btn_cafe_info" type="button" onclick="info_move('cafe')">카페정보</button>
                </div>
            </li>
            <li>
                <div style="margin-right:22px;">
                    |
                </div>
            </li>
            <li>
                <div>
                    <button class="btn_cafe_info" type="button" onclick="info_move('member')"
                        onclick="member_login_info($id)">나의활동</button>
                </div>
            </li>
        </ul>
        <div id="cafe_info">
            <div id="profile">
                <a><img src="<?= $result->image_path ?>" width="75px" height="75px"></a>
            </div>
            <div id="profile_info">
                <ul>
                    <li>
                        <img src="/assets/images/manager.png" width="15px" height="15px"><span
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
                    <a><img src="<?= $member->image_path ?>" width="75px" height="75px"></a>
                </div>
                <div id="profile_info">
                    <ul>
                        <li>
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
        <hr>
        <a href="">
            <div id="main_write">
                <a href=" " onclick="member_login_info()">
                    <p>카페 글쓰기</p>
                </a>
            </div>
        </a>
        <hr id="category_hr">
        <div id="all_list">
            <a href="/layout/full_board_list">전체글보기</a>
        </div>
        <hr>
        <?if($authority == 2){?>
        <div class="btn-group">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#addCategoryModal">+</button>
            <button type="button" id="delete_category" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#staticBackdrop">-</button>
        </div>
        <hr>
        <?}?>
        <!-- foreach문돌리기 -->
        <div id="category_list">
            <ul id="category_move">
                <? foreach ($category_list as $category) { ?>
                    <? if ($category->category_num != 0) { ?>
                        <li id="category_list_style_none">
                            <a href="/board/board_list?name=<?= $category->category_name ?>&num=<?= $category->category_num ?>">
                                <?= $category->category_name ?>
                            </a>
                        </li>
                        <hr>
                    <? } ?>
                <? } ?>
            </ul>
        </div>

        <div id="date_board">
            <span>최근게시글</span>
            <? foreach ($date_board as $board) { ?>
                <ul>
                    <li><a
                            href="/board/board_detail?category=<?= $board->category_num ?>&board_num=<?= $board->article_num ?>"><?= $board->title ?></a></li>
                </ul>
            <? } ?>
        </div>
    </div>

    <!-- 카테고리 추가 모달창 -->
    <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" tabindex="-1"
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

    <!-- 콘텐츠부분 -->
    <?= $content ?>

    <!-- 푸터부분 -->
    <footer class="py-5 mt-auto" id="footer">
        <a id="footer_move" href="/layout/full_board_list">Hansol CAFE</a>
    </footer>

</body>

</html>