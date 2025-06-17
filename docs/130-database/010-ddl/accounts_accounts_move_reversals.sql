CREATE TABLE Accounts_Accounts_Move_Reversals
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Company_Id INTEGER NOT NULL
    REFERENCES Companies
      ON DELETE CASCADE,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Reason     TEXT,
  Date       date    NOT NULL,
  Created_At datetime,
  Updated_At datetime
);
