CREATE DATABASE studyDB;

USE studyDB;

CREATE TABLE food (
    code VARCHAR(3) PRIMARY KEY,
    name VARCHAR(20),
    price INT
);

INSERT INTO
    food
VALUES ('A10', 'ぶどう', 300),
    ('A11', 'りんご', 150),
    ('A12', 'バナナ', 100),
    ('A13', 'オレンジ', 120),
    ('B20', 'ポテト', 200),
    ('B21', '人参', 180),
    ('B22', '大根', 250),
    ('B23', 'トマト', 280),
    ('C30', 'ハム', 290),
    ('C31', '牛乳', 250),
    ('C32', '卵', 190);

CREATE TABLE income (
    code VARCHAR(3) PRIMARY KEY,
    sales INT,
    kind VARCHAR(20)
);

INSERT INTO
    income
VALUES ('A10', 3, 'fruit'),
    ('A11', 5, 'fruit'),
    ('A12', 2, 'fruit'),
    ('A13', 10, 'fruit'),
    ('B20', 8, 'vegetable'),
    ('B21', 9, 'vegetable'),
    ('B22', 12, 'vegetable'),
    ('B23', 15, 'vegetable'),
    ('C30', 4, 'other'),
    ('C31', 6, 'other'),
    ('C32', 1, 'other');

create table goods (
    id varchar(10) primary key,
    name varchar(20),
    price int
);

INSERT INTO
    goods
VALUES ('A01', '机', 20000),
    ('B01', '冬布団', 15000),
    ('B02', '夏布団', 10000),
    ('C01', 'カーテン', 8000),
    ('D01', '椅子L', 4000),
    ('D02', '椅子M', 2000),
    ('E01', 'マット', 1500);

create table stock (
    id varchar(10) primary key,
    number int,
    maker varchar(20)
);

INSERT INTO
    stock
VALUES ('A01', 10, 'A工場'),
    ('B01', 18, 'B寝具店'),
    ('B02', 15, 'B寝具店'),
    ('C01', 7, 'B寝具店'),
    ('D01', 25, 'A工場'),
    ('D02', 30, 'A工場'),
    ('E01', 35, 'C雑貨店');

create table worker (
    id varchar(10) primary key,
    name varchar(20),
    age int
);

INSERT INTO
    worker
VALUES ('P01', '上野', 20),
    ('P02', '神田', 35),
    ('P03', '品川', 26),
    ('P04', '大崎', 31),
    ('P05', '大塚', 33);

create table pay (
    id varchar(10) primary key,
    salary int,
    entry int
);

INSERT INTO
    pay
VALUES ('P01', 900, 2015),
    ('P02', 850, 2010),
    ('P03', 980, 2000),
    ('P04', 1000, 2000),
    ('P05', 1050, 1990),
    ('P06', 1100, 1990);

-- workerテーブルにphoto列を追加
ALTER TABLE worker ADD photo VARCHAR(64);

-- workerテーブルの写真ファイル名を挿入
UPDATE worker
SET
    photo = CASE
        WHEN id = 'P01' THEN 'animal_buta.png'
        WHEN id = 'P02' THEN 'animal_inu.png'
        WHEN id = 'P03' THEN 'animal_mitsubachi.png'
        WHEN id = 'P04' THEN 'animal_neko.png'
        WHEN id = 'P05' THEN 'animal_usagi.png'
        ELSE photo
    END;

-- foodテーブルにphoto列を追加
ALTER TABLE food ADD photo VARCHAR(64);

-- foodテーブルの写真ファイル名を挿入
UPDATE food
SET
    photo = CASE
        WHEN code = 'A10' THEN 'animal_buta.png'
        WHEN code = 'A11' THEN 'animal_inu.png'
        WHEN code = 'A12' THEN 'animal_mitsubachi.png'
        WHEN code = 'A13' THEN 'animal_neko.png'
        WHEN code = 'B20' THEN 'animal_usagi.png'
        WHEN code = 'B21' THEN 'animal_buta.png'
        WHEN code = 'B22' THEN 'animal_inu.png'
        WHEN code = 'B23' THEN 'animal_mitsubachi.png'
        WHEN code = 'C30' THEN 'animal_neko.png'
        WHEN code = 'C31' THEN 'animal_usagi.png'
        WHEN code = 'C32' THEN 'animal_buta.png'
        ELSE photo
    END;

create table sushi (
    id INT primary key,
    name VARCHAR(64),
    price INT,
    buy INT,
    photo VARCHAR(255)
);

INSERT INTO
    sushi (id, name, price, buy, photo)
VALUES (1, "エビ", 100, 0, "ebi.png"),
    (
        2,
        "太巻き",
        120,
        0,
        "futomaki.png"
    ),
    (3, "イカ", 150, 0, "ika.png"),
    (4, "いくら", 200, 0, "ikura.png"),
    (
        5,
        "かっぱ巻き",
        90,
        0,
        "kappa.png"
    ),
    (
        6,
        "まぐろ",
        130,
        0,
        "maguro.png"
    ),
    (
        7,
        "しめサバ",
        150,
        0,
        "simesaba.png"
    ),
    (
        8,
        "たまご",
        100,
        0,
        "tamago.png"
    ),
    (
        9,
        "鉄火巻き",
        120,
        0,
        "tekka.png"
    ),
    (
        10,
        "ちらし寿司",
        210,
        0,
        "tirasi.png"
    );

drop table if exists hash;

create table hash (
    id VARCHAR(64) primary key,
    password VARCHAR(64)
);

DROP TABLE IF EXISTS shop;

create table shop (
    id VARCHAR(64) primary key,
    name VARCHAR(64),
    comment TEXT,
    price INT,
    stock INT,
    photo VARCHAR(255)
);

DROP TABLE IF EXISTS cart;

create table cart (
    id VARCHAR(64) primary key,
    purchase INT,
    after BOOLEAN
);

SELECT * FROM shop;

INSERT INTO
    shop (
        id,
        name,
        comment,
        price,
        stock,
        photo
    )
VALUES (
        "A01",
        "Bag",
        "",
        3800,
        3,
        "Bag.png"
    ),
    (
        "A02",
        "Ball",
        "",
        310,
        15,
        "ball.png"
    ),
    (
        "A03",
        "Banana",
        "",
        170,
        20,
        "banana.png"
    );

CREATE TABLE employee (
    id VARCHAR(10),
    name VARCHAR(20),
    password VARCHAR(64),
    PRIMARY KEY (id, name)
);

-- employeeRegisterで(id="P01", name="admin", password="password")を作成しておく

TRUNCATE employee;

CREATE TABLE attendance (
    id VARCHAR(10) NOT NULL,
    name VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    day_of_week VARCHAR(10) NOT NULL,
    check_in_time TIME,
    check_out_time TIME,
    overtime_minutes INT DEFAULT 0,
    late_night_overtime_minutes INT DEFAULT 0,
    holiday_overtime_minutes INT DEFAULT 0,
    overtime_pay INT DEFAULT 0,
    PRIMARY KEY (id, date)
);

DROP TABLE IF EXISTS attendance;