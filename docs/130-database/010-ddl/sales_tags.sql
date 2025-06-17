CREATE TABLE Sales_Tags
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Name       varchar NOT NULL,
  Color      varchar,
  Created_At datetime,
  Updated_At datetime
);
