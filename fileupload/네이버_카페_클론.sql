CREATE TABLE `member` (
	`user_id`	varchar	NOT NULL	COMMENT '아이디정보',
	`user_pw`	varchar	NOT NULL	COMMENT '비밀번호정보',
	`user_email`	varchar	NOT NULL	COMMENT '이메일정보',
	`user_name`	varchar	NOT NULL	COMMENT '유저이름',
	`user_birth`	varchar	NOT NULL	COMMENT '생년월일',
	`user_ex`	varchar	NOT NULL	COMMENT '자기소개',
	`image_path`	varchar	NULL	COMMENT '프사',
	`post_num`	int	NULL	COMMENT '게시글수',
	`comment`	int	NULL	COMMENT '작성한댓글수',
	`authority`	int	NOT NULL	DEFAULT 1	COMMENT '1번유저 2번관리자',
	`count`	int	NOT NULL	DEFAULT 0	COMMENT '로그인할때마다 1증가',
	`user_date`	date	NOT NULL	COMMENT 'currentimestamp'
);

CREATE TABLE `heart` (
	`article_num`	int	NOT NULL	COMMENT '게시반번호',
	`user_id2`	varchar	NOT NULL	COMMENT '아이디정보',
	`heart_num`	int	NOT NULL	COMMENT '누르면+1'
);

CREATE TABLE `category` (
	`category_num`	int	NOT NULL	COMMENT '카테고리번호',
	`category_name`	varchar	NOT NULL	COMMENT '카테고리이름'
);

CREATE TABLE `board` (
	`article_num`	int	NOT NULL	COMMENT '게시반번호',
	`user_id2`	varchar	NOT NULL	COMMENT '아이디정보',
	`category_num`	int	NOT NULL	COMMENT '카테고리번호',
	`groupOrd`	int	NOT NULL	DEFAULT 0	COMMENT '답글번호 순서',
	`groupLayer`	int	NOT NULL	DEFAULT 0	COMMENT '답글계층',
	`title`	varchar	NOT NULL	COMMENT '타이틀',
	`content`	varchar	NOT NULL	COMMENT '작성자',
	`date`	date	NOT NULL	COMMENT '현재시간기입',
	`nomal_status`	int	NOT NULL	DEFAULT 1	COMMENT '게시글 상태',
	`main_status`	int	NOT NULL	COMMENT '공지사항'
);

CREATE TABLE `reply` (
	`reply_num`	int	NOT NULL	COMMENT '댓글번호',
	`reply_parent`	int	NOT NULL	COMMENT '부모글',
	`reply_content`	varchar	NOT NULL	COMMENT '댓글내용',
	`reply_date`	date	NOT NULL	COMMENT 'currentimestamp',
	`article_num`	int	NOT NULL	COMMENT '게시반번호',
	`user_id2`	varchar	NOT NULL	COMMENT '아이디정보',
	`category_num`	int	NOT NULL	COMMENT '카테고리번호'
);

CREATE TABLE `file_upload` (
	`article_num`	int	NOT NULL	COMMENT '게시반번호',
	`file_path`	varchar	NOT NULL	COMMENT '파일경로'
);

ALTER TABLE `member` ADD CONSTRAINT `PK_MEMBER` PRIMARY KEY (
	`user_id`
);

ALTER TABLE `heart` ADD CONSTRAINT `PK_HEART` PRIMARY KEY (
	`article_num`,
	`user_id2`
);

ALTER TABLE `category` ADD CONSTRAINT `PK_CATEGORY` PRIMARY KEY (
	`category_num`
);

ALTER TABLE `board` ADD CONSTRAINT `PK_BOARD` PRIMARY KEY (
	`article_num`,
	`user_id2`,
	`category_num`
);

ALTER TABLE `reply` ADD CONSTRAINT `PK_REPLY` PRIMARY KEY (
	`reply_num`
);

ALTER TABLE `file_upload` ADD CONSTRAINT `PK_FILE_UPLOAD` PRIMARY KEY (
	`article_num`
);

ALTER TABLE `heart` ADD CONSTRAINT `FK_board_TO_heart_1` FOREIGN KEY (
	`article_num`
)
REFERENCES `board` (
	`article_num`
);

ALTER TABLE `heart` ADD CONSTRAINT `FK_board_TO_heart_2` FOREIGN KEY (
	`user_id2`
)
REFERENCES `board` (
	`user_id2`
);

ALTER TABLE `board` ADD CONSTRAINT `FK_member_TO_board_1` FOREIGN KEY (
	`user_id2`
)
REFERENCES `member` (
	`user_id`
);

ALTER TABLE `board` ADD CONSTRAINT `FK_category_TO_board_1` FOREIGN KEY (
	`category_num`
)
REFERENCES `category` (
	`category_num`
);

ALTER TABLE `file_upload` ADD CONSTRAINT `FK_board_TO_file_upload_1` FOREIGN KEY (
	`article_num`
)
REFERENCES `board` (
	`article_num`
);

