CREATE TABLE Sessions
(
  Id            varchar NOT NULL
    PRIMARY KEY,
  User_Id       INTEGER,
  Ip_Address    varchar,
  User_Agent    TEXT,
  Payload       TEXT    NOT NULL,
  Last_Activity INTEGER NOT NULL
);

CREATE INDEX Sessions_Last_Activity_Index
  ON Sessions (Last_Activity);

CREATE INDEX Sessions_User_Id_Index
  ON Sessions (User_Id);
