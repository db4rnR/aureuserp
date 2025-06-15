CREATE TABLE Recruitments_Degrees
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Sort       INTEGER DEFAULT '0',
  Name       varchar NOT NULL,
  Created_At datetime,
  Updated_At datetime
);
