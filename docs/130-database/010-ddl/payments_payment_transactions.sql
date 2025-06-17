CREATE TABLE Payments_Payment_Transactions
(
  Id                  INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                INTEGER,
  Move_Id             INTEGER                NOT NULL
    REFERENCES Accounts_Account_Moves
      ON DELETE RESTRICT,
  Journal_Id          INTEGER
                                             REFERENCES Accounts_Journals
                                               ON DELETE SET NULL,
  Company_Id          INTEGER
                                             REFERENCES Companies
                                               ON DELETE SET NULL,
  Statement_Id        INTEGER
                                             REFERENCES Accounts_Bank_Statements
                                               ON DELETE SET NULL,
  Partner_Id          INTEGER
                                             REFERENCES Partners_Partners
                                               ON DELETE SET NULL,
  Currency_Id         INTEGER
    REFERENCES Currencies
      ON DELETE RESTRICT,
  Foreign_Currency_Id INTEGER
    REFERENCES Currencies
      ON DELETE RESTRICT,
  Created_Id          INTEGER
                                             REFERENCES Users
                                               ON DELETE SET NULL,
  Account_Number      varchar,
  Partner_Name        varchar,
  Transaction_Type    varchar,
  Payment_Reference   varchar,
  Internal_Index      varchar,
  Transaction_Details TEXT,
  Amount              numeric    DEFAULT '0' NOT NULL,
  Amount_Currency     numeric    DEFAULT '0',
  Amount_Residual     numeric    DEFAULT '0',
  Is_Reconciled       tinyint(1) DEFAULT '0' NOT NULL,
  Created_At          datetime,
  Updated_At          datetime
);
