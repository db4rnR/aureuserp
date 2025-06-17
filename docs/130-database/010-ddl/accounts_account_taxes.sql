CREATE TABLE Accounts_Account_Taxes
(
  Account_Id INTEGER NOT NULL
    REFERENCES Accounts_Accounts
      ON DELETE CASCADE,
  Tax_Id     INTEGER NOT NULL
    REFERENCES Accounts_Taxes
      ON DELETE CASCADE
);
