CREATE DATABASE IF NOT EXISTS mail_homework CHARACTER SET utf8mb4;
USE mail_homework;

CREATE TABLE email_list (
    No INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE
);

INSERT INTO email_list (email) VALUES ('a1133352@mail.nuk.edu.tw');

