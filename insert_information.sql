BEGIN;

USE QnADB;
INSERT INTO users(uid, name, password, privilege) VALUES(0, "Administrator", "bf64262c155d9726fe35e5d6b66731a1", "admin");
INSERT INTO question(qid, content, uid, uploadtime) VALUES(0, "为什么数据库这么难？", 0, now());
INSERT INTO answer(qid, uid, content) VALUES(0, 0, "我不知道");

COMMIT;