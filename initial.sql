DROP DATABASE jjl;
CREATE DATABASE jjl;
USE jjl;

/* create table */
CREATE TABLE Hood(
  hood_id integer not null auto_increment,
  h_name varchar(100),

  h_s decimal(5, 2),
  h_w decimal(5, 2),
  h_n decimal(5, 2),
  h_e decimal(5, 2),

  primary key(hood_id)
);

CREATE TABLE Block(
  block_id integer not null auto_increment,
  b_name varchar(100),
  hood_id integer,

  b_s decimal(5, 2),
  b_w decimal(5, 2),
  b_n decimal(5, 2),
  b_e decimal(5, 2),

  primary key(block_id),
  foreign key(hood_id) references Hood(hood_id)
);

CREATE TABLE User(
  uid        integer not null auto_increment,
  password  varchar(20) not null,
  u_name    varchar(20) not null,

  u_profile varchar(100),
  u_photo   varchar(30),
  u_gender  enum('f', 'm', 'u'),

  address   varchar(40),
  block_id  integer,

  lastVisit datetime default now(),

  primary key(uid),
  foreign key(block_id) references Block(block_id)
);

CREATE TABLE Neighbor(
  uid1 integer not null comment 'uid1 is following uid2',
  uid2 integer not null comment 'uid2 is followed by uid1',

  time datetime default now(),

  primary key(uid1, uid2),
  foreign key(uid1) references User(uid),
  foreign key(uid2) references User(uid)
);

CREATE TABLE Friends(

  uid1 integer not null comment 'uid1 is the sender',
  uid2 integer not null comment 'uid2 is the receiver',
  state enum('pending', 'accepted', 'active', 'rejected', '1-inactive', '2-inactive'),

  primary key(uid1, uid2),
  foreign key(uid1) references User(uid),
  foreign key(uid2) references User(uid)
);

CREATE TABLE  Messages(
  mid integer not null auto_increment,

  m_type enum('private', 'friend', 'neighbor'),

  m_title   varchar(20),
  m_content varchar(100),
  m_time    datetime default now(),

  m_from  integer,
  m_to    integer comment 'null when m_type is hood',
  m_hood  integer comment 'not null only when m_type is hood',

  primary key(mid),
  foreign key(m_hood) references Hood(hood_id)
);

CREATE TABLE Comments(
  cid integer not null auto_increment,
  mid integer,

  c_from integer,
  c_to   integer,

  c_time datetime,
  c_content varchar(100) not null,

  primary key(cid),
  foreign key(c_from) references User(uid),
  foreign key(c_to) references User(uid),
  foreign key(mid) references Messages(mid)
);

CREATE TABLE JoinBlock(
  uid      integer not null,
  block_id integer not null,

  state enum('pending', 'accepted', 'rejected'),

  approvers varchar(101) default '' comment 'store a string like 1,2,3',
  refusers  varchar(101) default '' comment 'store a string like 2,3,5',

  foreign key(uid) references User(uid),
  foreign key(block_id) references Block(block_id)
);

/* insert records */
INSERT INTO Hood(hood_id, h_name, h_s, h_w, h_n, h_e)
          VALUES(1,      'hood1', 1.1, 1.1, 1.1, 1.1),
                (2,      'hood2', 2.2, 2.2, 2.2, 2.2),
                (3,      'hood3', 3.3, 3.3, 3.3, 3.3),
                (4,      'hood4', 4.4, 4.4, 4.4, 4.4),
                (5,      'hood5', 5.5, 5.5, 5.5, 5.5);

INSERT INTO Block(block_id, b_name, hood_id, b_s, b_w, b_n, b_e)
           VALUES(1,      'block1',       1, 1.1, 1.1, 1.1, 1.1),
                 (2,      'block2',       1, 2.2, 2.2, 2.2, 2.2),
                 (3,      'block3',       1, 3.3, 3.3, 3.3, 3.3),
                 (4,      'block4',       2, 4.4, 4.4, 4.4, 4.4),
                 (5,      'block5',       2, 5.5, 5.5, 5.5, 5.5),
                 (6,      'block6',       3, 6.6, 6.6, 6.6, 6.6),
                 (7,      'block7',       4, 7.7, 7.7, 7.7, 7.7),
                 (8,      'block8',       4, 8.8, 8.8, 8.8, 8.8),
                 (9,      'block9',       5, 9.9, 9.9, 9.9, 9.9);

INSERT INTO User(uid, password,  u_name,  u_profile,       u_photo, u_gender, block_id, address)
           VALUES(  1,    'jjl', 'user1', 'profile1', 'avatar1.png',      'M',       1, 'address1'),
                 (  2,    'jjl', 'user2', 'profile2', 'avatar2.png',      'M',       1, 'address2'),
                 (  3,    'jjl', 'user3', 'profile3', 'avatar3.png',      'M',       1, 'address3'),
                 (  4,    'jjl', 'user4', 'profile4', 'avatar4.png',      'M',       2, 'address4'),
                 (  5,    'jjl', 'user5', 'profile5', 'avatar5.png',      'M',       2, 'address5'),
                 (  6,    'jjl', 'user6', 'profile6', 'avatar6.png',      'M',       2, 'address6'),
                 (  7,    'jjl', 'user7', 'profile7', 'avatar7.png',      'M',       3, 'address7'),
                 (  8,    'jjl', 'user8', 'profile8', 'avatar8.png',      'M',       4, 'address8'),
                 (  9,    'jjl', 'user9', 'profile9', 'avatar9.png',      'M',       4, 'address9');

INSERT INTO Friends(uid1, uid2,      state)
             VALUES(   1,    2,  'pending'),
                   (   1,    3,  'accepted'),
                   (   1,    4,  'active'),
                   (   1,    5,  'rejected'),
                   (   1,    6,  '1-inactive'),
                   (   1,    7,  '2-inactive');

INSERT INTO Neighbor(uid1, uid2)
              VALUES(   2,    1),
                    (   3,    1),
                    (   4,    1),
                    (   5,    1);

INSERT INTO Messages(    m_type,  m_from,  m_to,       m_title,             m_content,      m_hood)
              VALUES( 'private',       1,     3,  'private message', 'from user1 to user3',  null),
                    (  'friend',       1,     4,  'friend  message', 'from user1 to user4',  null),
                    ('neighbor',       1,  null,  'block   message', 'from user1 to block1', null);

INSERT INTO JoinBlock(uid,  block_id,      state,  approvers,  refusers)
               VALUES(  1,         2,  'pending',         '',        ''),
                     (  2,         4, 'rejected',         '',     '8,9'),
                     (  3,         1, 'accepted',      '1,2',        '');

/* query */
SELECT * FROM Hood;
SELECT * FROM Block;
SELECT * FROM User;
SELECT * FROM Friends;
SELECT * FROM Neighbor;
SELECT * FROM Messages;
SELECT * FROM JoinBlock;

/*
 用户相关：
 1. 注册
 INSERT INTO User VALUES(...);

 2. 登录
 SELECT * FROM User WHERE u_name = 'name' and password = 'password';

 3. 修改资料
 UPDATE User SET ...;

 好友相关:
 1. 申请好友
 INSERT INTO Friends VALUES(senderId, recerverId, 'pending');

 2. 查询收到的好友请求
 SELECT * FROM Friends WHERE state = 'pending' and uid2 = currentUserId;

 3. 查询发出去的好友请求状态（被接受？被拒绝？未处理？）
 SELECT uid2, state FROM Friends WHERE uid1 = currentUserId;

 4. 接受好友请求 
 UPDATE Friends SET state = 'accepted' WHERE uid1 = senderId and uid2 = currentUserId;

 5. 拒绝好友请求
 UPDATE Friends SET state = 'rejected' WHERE uid1 = senderId and uid2 = currentUserId;

 关注相关：
 1. 查询关注列表
 SELECT * FROM Neighbor WHERE uid1 = currentUserId;

 2. 查询被关注消息（目前设计无法实现，要实现需要在neighbor中添加state字段）
 3. 关注、取消关注某用户
 INSERT INTO Neighbor VALUES(userId, currentUserId);
 REMOVE FROM Neighbor WHERE uid1 = userId and uid2 = currentUserId;

 消息相关：
 1. 查询未读/已读私信消息
 SELECT * FROM Messages WHERE m_type = 'private' and m_to = currentUserId and m_time > lastVisit;

 2. 查询未读/已读好友发的消息
 3. 查询未读/已读关注人发的消息
 4. 发送私信消息
 5. 发送好友消息
 6. 发送关注者(neighbor)消息

 区域相关：
 1. 查询所在区域新成员请求
 2. 接受/拒绝新成员请求
 3. 申请加入新区域
 4. 查询发出去的区域请求结果（被接受？被拒绝？未处理？）
 4. 查询所在区域的用户列表
 */





