CREATE TABLE Accounts_Tax_Taxes
(
  Parent_Tax_Id INTEGER NOT NULL
    REFERENCES Accounts_Taxes
      ON DELETE CASCADE,
  Child_Tax_Id  INTEGER NOT NULL
    REFERENCES Accounts_Taxes
      ON DELETE CASCADE
);
