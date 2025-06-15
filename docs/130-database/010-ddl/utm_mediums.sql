CREATE TABLE Utm_Mediums
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Name       varchar NOT NULL,
  Created_At datetime,
  Updated_At datetime
);
