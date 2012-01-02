# Запускать только на пустой БД и только один раз!
# 
# Все значения первичных ключей актуальны только при первом запуске,
# при последующем они будут другими.

INSERT INTO regions (id, name) VALUES (1, '(Неопределенный)');

INSERT INTO web_users(id, login, pwd_hash, is_enabled, region_id) VALUES (1, 'admin', 'f99cf418670b465d4ee37b0bac36265e', 1, 1);

INSERT INTO permissions (id, description) VALUES (1, 'делать все что вздумается, ибо Админ');
INSERT INTO permissions (id, description) VALUES (2, 'просматривать список пользователей');
INSERT INTO permissions (id, description) VALUES (3, 'просматривать пользователя');
INSERT INTO permissions (id, description) VALUES (4, 'управлять учетной записью пользователя');
INSERT INTO permissions (id, description) VALUES (5, 'управлять правами');
INSERT INTO permissions (id, description) VALUES (6, 'просматривать список команд');
INSERT INTO permissions (id, description) VALUES (7, 'просматривать команду');
INSERT INTO permissions (id, description) VALUES (8, 'управлять командой');
INSERT INTO permissions (id, description) VALUES (9, 'просматривать список игр');
INSERT INTO permissions (id, description) VALUES (10, 'просматривать игру');
INSERT INTO permissions (id, description) VALUES (11, 'участвовать в проведении игры');
INSERT INTO permissions (id, description) VALUES (12, 'руководить игрой');
INSERT INTO permissions (id, description) VALUES (13, 'управлять игрой');
INSERT INTO permissions (id, description) VALUES (14, 'писать любое количество статей');
INSERT INTO permissions (id, description) VALUES (15, 'модерировать статью');

INSERT INTO granted_permissions(web_user_id, permission_id) VALUES (1, 1);

INSERT INTO articles (id, name, path, text, web_user_id) VALUES (1, 'Разделы', '', "[_]\r\n[url=/article/index]Все статьи[/url] в алфавитном порядке\r\n\r\n[[Новости]]\r\n\r\n[[Инструкции]]\r\n[_]", 1);
INSERT INTO articles (id, name, path, text, web_user_id) VALUES (2, 'Новости', 'Разделы', '[[Новости-(Общие)]]', 1);
INSERT INTO articles (id, name, path, text, web_user_id) VALUES (3, 'Новости-(Общие)', 'Разделы\Новости', 'Здесь будут общие новости.', 1);
