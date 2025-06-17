CREATE TABLE Utm_Stages
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort       INTEGER,
  Name       varchar NOT NULL,
  Created_By INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Created_At datetime,
  Updated_At datetime
);
