# creates user and database

CREATE USER 'backup'@'localhost' IDENTIFIED BY 'backup';
CREATE DATABASE backup;
GRANT ALL ON backup.* TO 'backup'@'localhost';