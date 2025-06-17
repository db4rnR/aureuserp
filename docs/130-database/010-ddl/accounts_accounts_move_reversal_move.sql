CREATE TABLE Accounts_Accounts_Move_Reversal_Move
(
  Move_Id     INTEGER NOT NULL
    REFERENCES Accounts_Account_Moves
      ON DELETE CASCADE,
  Reversal_Id INTEGER NOT NULL
    REFERENCES Accounts_Accounts_Move_Reversals
      ON DELETE CASCADE
);
