-- FIXME: Primary keys???
CREATE TABLE /*_*/chat (
  chat_user_name varchar(255),
  chat_user_id int(10),
  chat_message text,
  chat_type text,
  chat_timestamp binary(12),
  chat_to_name varchar(255),
  chat_to_id int(10)
) /*$wgDBTableOptions*/;

CREATE TABLE /*_*/chat_users (
  cu_user_name varchar(255),
  cu_user_id int(10),
  cu_timestamp binary(12)
) /*$wgDBTableOptions*/;