CREATE TABLE Accounts_Journal_Accounts
(
  Journal_Id INTEGER NOT NULL
    REFERENCES Accounts_Journals
      ON DELETE CASCADE,
  Account_Id INTEGER NOT NULL
    REFERENCES Accounts_Accounts
      ON DELETE CASCADE
);
