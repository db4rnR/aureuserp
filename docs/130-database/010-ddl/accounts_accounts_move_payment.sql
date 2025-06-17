CREATE TABLE Accounts_Accounts_Move_Payment
(
  Invoice_Id INTEGER NOT NULL
    REFERENCES Accounts_Account_Moves
      ON DELETE CASCADE,
  Payment_Id INTEGER NOT NULL
    REFERENCES Accounts_Account_Payments
      ON DELETE CASCADE
);
