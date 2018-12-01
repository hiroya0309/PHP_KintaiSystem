CREATE DATABASE kintai DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

usersテーブル　//ユーザー登録
CREATE TABLE kintai. users (
id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
username VARCHAR( 64 ) NOT NULL ,
email VARCHAR( 128 ) NOT NULL ,
password VARCHAR( 100 ) NOT NULL ,
created_at datetime NOT NULL ,
updated_at datetime NOT NULL ,
UNIQUE (email)
);

adminテーブル　//管理者登録
CREATE TABLE kintai. admins (
id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
username VARCHAR( 64 ) NOT NULL ,
email VARCHAR( 128 ) NOT NULL ,
password VARCHAR( 100 ) NOT NULL ,
created_at datetime NOT NULL ,
updated_at datetime NOT NULL ,
UNIQUE (email)
);

workテーブル　//勤怠管理
CREATE TABLE kintai.work (
id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
user_id INT( 11 ) NOT NULL ,
form VARCHAR( 64 ) DEFAULT NULL ,
date date NOT NULL ,
start_time time NOT NULL ,
finish_time time NOT NULL ,
breck_time time NOT NULL,
remarks TEXT,
created_at datetime NOT NULL,
updated_at datetime NOT NULL
);

scheduleテーブル　//予定表
CREATE TABLE kintai.schedule (
id INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
user_id INT( 11 ) NOT NULL ,
date date NOT NULL ,
category VARCHAR( 64 ) NOT NULL ,
plan TEXT,
created_at datetime NOT NULL,
updated_at datetime NOT NULL
);

//事前に登録が必要
INSERT INTO work(user_id,date) VALUES(1,'2018-12-01'),(1,'2018-12-02'),(1,'2018-12-03'),(1,'2018-12-04'),(1,'2018-12-05'),(1,'2018-12-06'),(1,'2018-12-12'),(1,'2018-12-08'),
(1,'2018-12-09'),(1,'2018-12-10'),(1,'2018-12-11'),(1,'2018-12-12'),(1,'2018-12-13'),(1,'2018-12-14'),(1,'2018-12-15'),(1,'2018-12-16'),(1,'2018-12-17'),(1,'2018-12-18'),
(1,'2018-12-19'),(1,'2018-12-20'),(1,'2018-12-21'),(1,'2018-12-22'),(1,'2018-12-23'),(1,'2018-12-24'),(1,'2018-12-25'),(1,'2018-12-26'),(1,'2018-12-27'),(1,'2018-12-28'),(1,'2018-12-29'),(1,'2018-12-30'),(1,'2018-12-31');

INSERT INTO work(user_id,date) VALUES(1,'2019-1-01'),(1,'2019-01-02'),(1,'2019-01-03'),(1,'2019-01-04'),(1,'2019-01-05'),(1,'2019-01-06'),(1,'2019-01-12'),(1,'2019-01-08'),
(1,'2019-01-09'),(1,'2019-01-10'),(1,'2019-01-11'),(1,'2019-01-12'),(1,'2019-01-13'),(1,'2019-01-14'),(1,'2019-01-15'),(1,'2019-01-16'),(1,'2019-01-17'),(1,'2019-01-18'),
(1,'2019-01-19'),(1,'2019-01-20'),(1,'2019-01-21'),(1,'2019-01-22'),(1,'2019-01-23'),(1,'2019-01-24'),(1,'2019-01-25'),(1,'2019-01-26'),(1,'2019-01-27'),(1,'2019-01-28'),(1,'2019-01-29'),(1,'2019-01-30'),(1,'2019-01-31');


INSERT INTO work(user_id,date) VALUES(1,'2019-1-01'),(1,'2019-02-02'),(1,'2019-02-03'),(1,'2019-02-04'),(1,'2019-02-05'),(1,'2019-02-06'),(1,'2019-02-12'),(1,'2019-02-08'),
(1,'2019-02-09'),(1,'2019-02-10'),(1,'2019-02-11'),(1,'2019-02-12'),(1,'2019-02-13'),(1,'2019-02-14'),(1,'2019-02-15'),(1,'2019-02-16'),(1,'2019-02-17'),(1,'2019-02-18'),
(1,'2019-02-19'),(1,'2019-02-20'),(1,'2019-02-21'),(1,'2019-02-22'),(1,'2019-02-23'),(1,'2019-02-24'),(1,'2019-02-25'),(1,'2019-02-26'),(1,'2019-02-27'),(1,'2019-02-28');



