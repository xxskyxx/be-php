INSERT INTO web_users(login, pwd_hash, is_enabled) VALUES ('admin', 'f99cf418670b465d4ee37b0bac36265e', 1);

INSERT INTO permissions (id, description) VALUES ('1', 'делать все что вздумается, ибо Админ');
INSERT INTO permissions (id, description) VALUES ('2', 'просматривать список пользователей');
INSERT INTO permissions (id, description) VALUES ('3', 'просматривать пользователя');
INSERT INTO permissions (id, description) VALUES ('4', 'управлять учетной записью пользователя');
INSERT INTO permissions (id, description) VALUES ('5', 'управлять правами');
INSERT INTO permissions (id, description) VALUES ('6', 'просматривать список команд');
INSERT INTO permissions (id, description) VALUES ('7', 'просматривать команду');
INSERT INTO permissions (id, description) VALUES ('8', 'управлять командой');
INSERT INTO permissions (id, description) VALUES ('9', 'просматривать список игр');
INSERT INTO permissions (id, description) VALUES ('10', 'просматривать игру');
INSERT INTO permissions (id, description) VALUES ('11', 'участвовать в проведении игры');
INSERT INTO permissions (id, description) VALUES ('12', 'руководить игрой');
INSERT INTO permissions (id, description) VALUES ('13', 'управлять игрой');
INSERT INTO permissions (id, description) VALUES ('14', 'модерировать блог');

INSERT INTO granted_permissions(web_user_id, permission_id) VALUES ('1', '1');

INSERT INTO blogs (id) VALUES ('1');
INSERT INTO posts (blog_id, web_user_id, text, create_time) VALUES ('1', '1', 'Сайт запущен!', 0);
INSERT INTO comments (post_id, web_user_id, text, create_time) VALUES ('1', '1', 'Это радует!', 0);