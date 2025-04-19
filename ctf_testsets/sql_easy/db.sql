/*
 * db.sql 文件
 *
 * 此文件用于初始化你的 MySQL 数据库。
 * 它将在 Docker 容器启动时运行，
 * 并执行所有的 SQL 命令来设置你的数据库。
 *
 * 你可以在这里创建你的数据库，创建表，
 * 插入数据，或执行任何其他的 SQL 命令。
 *
 * 例如：
 *   CREATE DATABASE IF NOT EXISTS your_database;
 *   USE your_database;
 *   CREATE TABLE your_table (...);
 *   INSERT INTO your_table VALUES (...);
 *
 * 请根据你的需要修改此文件，
 */

CREATE DATABASE ctf;
use ctf;
create table users (id varchar(300),username varchar(300),password varchar(300));
insert into users values('1','tanji','flag{sql_injection_cwe_89}');
CREATE TABLE players (
                         id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                         username VARCHAR(30) NOT NULL,
                         password VARCHAR(30) NOT NULL
);

INSERT INTO players (username, password) VALUES
                                             ('player3', 'password3'),
                                             ('admin', 'flag{easy_sql}'),
                                             ('player2', 'password2');
