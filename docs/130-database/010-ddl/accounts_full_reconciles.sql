CREATE TABLE Accounts_Full_Reconciles
(
  Id               INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Exchange_Move_Id INTEGER
                           REFERENCES Accounts_Account_Moves
                             ON DELETE SET NULL,
  Created_Id       INTEGER
                           REFERENCES Users
                             ON DELETE SET NULL,
  Created_At       datetime,
  Updated_At       datetime
);
