CREATE TABLE Accounts_Incoterms
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Code       varchar NOT NULL,
  Name       varchar NOT NULL,
  Deleted_At datetime,
  Created_At datetime,
  Updated_At datetime
);
