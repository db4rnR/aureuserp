CREATE TABLE Accounts_Payment_Method_Lines
(
  Id                 INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort               INTEGER,
  Payment_Method_Id  INTEGER
    REFERENCES Accounts_Payment_Methods
      ON DELETE RESTRICT,
  Payment_Account_Id INTEGER
    REFERENCES Accounts_Accounts
      ON DELETE RESTRICT,
  Journal_Id         INTEGER
    REFERENCES Accounts_Journals
      ON DELETE RESTRICT,
  Creator_Id         INTEGER
                             REFERENCES Users
                               ON DELETE SET NULL,
  Name               varchar,
  Created_At         datetime,
  Updated_At         datetime
);
