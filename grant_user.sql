BEGIN;
DROP USER IF EXISTS 'admin'@'localhost';
DROP USER IF EXISTS 'user'@'%';
DROP USER IF EXISTS 'guest'@'%';

CREATE USER 'admin'@'localhost' IDENTIFIED BY 'v4S#k0';
CREATE USER 'user'@'%' IDENTIFIED BY 'VEk8qg';
CREATE USER 'guest'@'%' IDENTIFIED BY '100000';


GRANT ALL PRIVILEGES ON QnADB.* TO 'admin'@'localhost';
CREATE VIEW QnADB.user_wp AS SELECT name, password, avatar FROM QnADB.users;
GRANT SELECT ON QnADB.users TO 'user';
GRANT UPDATE ON QnADB.user_wp TO 'user';
GRANT SELECT, INSERT, UPDATE, DELETE ON QnADB.question TO 'user';
GRANT SELECT, INSERT, UPDATE, DELETE ON QnADB.answer TO 'user';
GRANT INSERT, SELECT ON QnADB.users TO 'guest';
GRANT SELECT ON QnADB.question TO 'guest';
GRANT SELECT ON QnADB.answer TO 'guest';

FLUSH PRIVILEGES;
COMMIT;