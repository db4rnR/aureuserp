CREATE TABLE Accounts_Account_Payment_Register_Move_Lines
(
  Payment_Register_Id INTEGER NOT NULL
    REFERENCES Accounts_Payment_Registers
      ON DELETE CASCADE,
  Move_Line_Id        INTEGER NOT NULL
    REFERENCES Accounts_Account_Move_Lines
      ON DELETE CASCADE
);
