CREATE TABLE Projects_Tags
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name       varchar NOT NULL,
  Color      varchar,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Deleted_At datetime,
  Created_At datetime,
  Updated_At datetime
);

CREATE UNIQUE INDEX Projects_Tags_Name_Unique
  ON Projects_Tags (Name);
