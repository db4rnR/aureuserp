CREATE TABLE Partners_Titles
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name       varchar NOT NULL,
  Short_Name varchar NOT NULL,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Created_At datetime,
  Updated_At datetime
);
