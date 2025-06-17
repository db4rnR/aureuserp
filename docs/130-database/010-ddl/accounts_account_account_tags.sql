CREATE TABLE Accounts_Account_Account_Tags
(
  Account_Id     INTEGER NOT NULL
    REFERENCES Accounts_Accounts
      ON DELETE CASCADE,
  Account_Tag_Id INTEGER NOT NULL
    REFERENCES Accounts_Account_Tags
      ON DELETE CASCADE
);
