CREATE TABLE Accounts_Accounts_Move_Line_Taxes
(
  Move_Line_Id INTEGER NOT NULL
    REFERENCES Accounts_Account_Move_Lines
      ON DELETE CASCADE,
  Tax_Id       INTEGER NOT NULL
    REFERENCES Accounts_Taxes
      ON DELETE CASCADE
);
