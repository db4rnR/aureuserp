CREATE TABLE Products_Attributes
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name       varchar NOT NULL,
  Type       varchar NOT NULL,
  Sort       INTEGER,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Deleted_At datetime,
  Created_At datetime,
  Updated_At datetime
);
