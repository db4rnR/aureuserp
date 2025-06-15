CREATE TABLE Accounts_Partial_Reconciles
(
  Id                     INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Debit_Move_Id          INTEGER
    REFERENCES Accounts_Account_Moves
      ON DELETE RESTRICT,
  Credit_Move_Id         INTEGER
    REFERENCES Accounts_Account_Moves
      ON DELETE RESTRICT,
  Full_Reconcile_Id      INTEGER
                                 REFERENCES Accounts_Full_Reconciles
                                   ON DELETE SET NULL,
  Exchange_Move_Id       INTEGER
                                 REFERENCES Accounts_Account_Moves
                                   ON DELETE SET NULL,
  Debit_Currency_Id      INTEGER
                                 REFERENCES Currencies
                                   ON DELETE SET NULL,
  Credit_Currency_Id     INTEGER
                                 REFERENCES Currencies
                                   ON DELETE SET NULL,
  Company_Id             INTEGER
                                 REFERENCES Companies
                                   ON DELETE SET NULL,
  Created_By             INTEGER
                                 REFERENCES Users
                                   ON DELETE SET NULL,
  Max_Date               date,
  Amount                 numeric,
  Debit_Amount_Currency  numeric,
  Credit_Amount_Currency numeric,
  Created_At             datetime,
  Updated_At             datetime
);
