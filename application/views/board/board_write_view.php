<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <title>게시판 글쓰기/글수정</title>
    <!-- 부트스트랩 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <!-- 파비콘에러? -->
    <link rel="icon" href="data:;base64,iVBORw0KGgo=">
    <link rel="stylesheet" href="/assets/css/board/board_write.css">
    <script src="/assets/js/board/board_write.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- tinymce -->
    <script
        src="https://cdn.tiny.cloud/1/35y22h3oeycwqdph0547cq5a3s51r0o7is1hfpihgjyavf7t/tinymce/6/tinymce.min.js"></script>
</head>

<body>
    <div class="container" id="logo">
        <a href="/layout"><img src="/assets/images/icon-cafe.png" width="75px" height="75px"></a>
    </div>
    <a href="/layout">
        <div id="banner">
        <img src="/assets/images/banner.jpg" width="1175px" height="300px">
        </div>
    </a>
    <div id="write_title">
        <? if ($write == 1 || $write == 3) { ?>
            <h3>카페 글쓰기</h3>
        <? } else if ($write == 2) { ?>
                <h3>카페 글수정</h3>
        <? } else { ?>
                <h3>오류</h3>
        <? } ?>
    </div>

    <? if ($write == 1) { ?>
        <form id="write_form" action="/board/board_write/board_create" method="post" enctype="multipart/form-data">
            <div id="btn_group">
                <ul>
                    <li>
                        <button type="button" class="btn btn-outline-secondary" onclick="goBack()"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            돌아가기
                        </button>
                    </li>
                    <li>
                        <button type="button" class="btn btn-outline-secondary" id="previewButton"
                            onclick="showPreview(event)"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            미리보기
                        </button>
                    </li>
                    <li>
                        <button type="submit" class="btn btn-outline-secondary"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            등록
                        </button>
                    </li>
                </ul>
            </div>
            <hr>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="announcement" id="announcementCheckbox">
                <label class="form-check-label" for="announcementCheckbox">공지사항 등록</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="disclosure" id="disclosureCheckbox">
                <label class="form-check-label" for="disclosureCheckbox">전체공개(비회원)</label>
            </div>
            <div class="filebox">
                <!-- 파일업로드 -->
                <label for="fileupload">파일 업로드</label>
                <input type="file" name="file" id="fileupload" onchange="fileName()">
                <div id="fileSizeError">파일 크기가 너무 큽니다<br> 2mb 이하의 파일을 업로드하세요.<br>파일 업로드가 취소됩니다.</div>
                <span id="file_info"></span>
            </div>
            <select class="move" name="category_pick">
                <? foreach ($result as $category_list) { ?>
                    <? if ($category_list['category_num'] != 0) { ?>
                        <option value="<?= $category_list['category_num'] ?>"><?= $category_list['category_name'] ?></option>
                    <? } ?>
                <? } ?>
            </select>

            <input class="move" type="text" id="title" name="title" placeholder="제목">

            <textarea class="hahee" id="f_content" name="content" placeholder="내용"></textarea>
        </form>
    <? } ?>


    <!-- 글수정부분 -->

    <? if ($write == 2) { ?>
        <form id="write_form" action="/board/board_write/board_modify" method="post" enctype="multipart/form-data">
            <div id="btn_group">
                <ul>
                    <li>
                        <button type="button" class="btn btn-outline-secondary" onclick="goBack()"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            돌아가기
                        </button>
                    </li>
                    <li>
                        <button type="button" class="btn btn-outline-secondary" id="previewButton"
                            onclick="showPreview(event)"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            미리보기
                        </button>
                    </li>
                    <li>
                        <button type="submit" class="btn btn-outline-secondary"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            등록
                        </button>
                    </li>
                </ul>
            </div>
            <hr>
            <input type="hidden" name="board_num" value="<?= $board->article_num ?>">

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="announcement" id="announcementCheckbox">
                <label class="form-check-label" for="announcementCheckbox">공지사항 등록</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="disclosure" id="disclosureCheckbox">
                <label class="form-check-label" for="disclosureCheckbox">전체공개(비회원)</label>
            </div>
            <div class="filebox">
                <!-- 파일업로드 -->
                <label for="fileupload">파일 업로드</label>
                <input type="file" name="file" id="fileupload" onchange="fileName()">
                <div id="fileSizeError">파일 크기가 너무 큽니다<br> 2mb 이하의 파일을 업로드하세요.<br>파일 업로드가 취소됩니다.</div>
                <span id="file_info"></span>
            </div>
            <select class="move" name="category_pick">
                <? foreach ($result as $category_list) { ?>
                    <? if ($category_list['category_num'] != 0) { ?>
                        <option value="<?= $category_list['category_num'] ?>"><?= $category_list['category_name'] ?></option>
                    <? } ?>
                <? } ?>
            </select>

            <input class="move" type="text" id="title" name="title" value="<?= $board->title ?>" required>

            <textarea class="hahee" id="f_content" name="content" required><?= $board->content ?></textarea>
        </form>
    <? } ?>

    <!-- 답게시글 다는부분 -->
    <? if ($write == 3) { ?>
        <form id="write_form" action="/board/board_write/reply_board_write" method="post" enctype="multipart/form-data">
            <div id="btn_group">
                <ul>
                    <li>
                        <button type="button" class="btn btn-outline-secondary" onclick="goBack()"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            돌아가기
                        </button>
                    </li>
                    <li>
                        <button type="button" class="btn btn-outline-secondary" id="previewButton"
                            onclick="showPreview(event)"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            미리보기
                        </button>
                    </li>
                    <li>
                        <button type="submit" class="btn btn-outline-secondary"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            등록
                        </button>
                    </li>
                </ul>
            </div>
            <hr>

            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="announcement" id="announcementCheckbox">
                <label class="form-check-label" for="announcementCheckbox">공지사항 등록</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" name="disclosure" id="disclosureCheckbox">
                <label class="form-check-label" for="disclosureCheckbox">전체공개(비회원)</label>
            </div>
            <div class="filebox">
                <!-- 파일업로드 -->
                <label for="fileupload">파일 업로드</label>
                <input type="file" name="file" id="fileupload" onchange="fileName()">
                <div id="fileSizeError">파일 크기가 너무 큽니다<br> 2mb 이하의 파일을 업로드하세요.<br>파일 업로드가 취소됩니다.</div>
                <span id="file_info"></span>
            </div>
            <input class="move" name="category_pick" type="hidden" value="<?= $parent_info->category_num ?>">


            <input type="hidden" id="parent_num" name="parent_num" value="<?= $article_num ?>">
            <input class="move" type="text" id="title" name="title" placeholder="<?= $parent_info->title ?>">

            <textarea id="f_content" name="content" placeholder="내용"></textarea>
        </form>
    <? } ?>

</body>

</html>