CREATE TABLE Accounts_Account_Journals
(
  Account_Id INTEGER NOT NULL
    REFERENCES Accounts_Accounts
      ON DELETE CASCADE,
  Journal_Id INTEGER NOT NULL
    REFERENCES Accounts_Journals
      ON DELETE CASCADE
);
