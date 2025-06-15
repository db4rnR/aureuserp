CREATE TABLE Accounts_Bank_Statement_Lines
(
  Id                  INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                INTEGER,
  Journal_Id          INTEGER
                                             REFERENCES Accounts_Journals
                                               ON DELETE SET NULL,
  Company_Id          INTEGER
                                             REFERENCES Companies
                                               ON DELETE SET NULL,
  Statement_Id        INTEGER
    REFERENCES Accounts_Bank_Statements
      ON DELETE CASCADE,
  Partner_Id          INTEGER
                                             REFERENCES Partners_Partners
                                               ON DELETE SET NULL,
  Currency_Id         INTEGER
                                             REFERENCES Currencies
                                               ON DELETE SET NULL,
  Foreign_Currency_Id INTEGER
                                             REFERENCES Currencies
                                               ON DELETE SET NULL,
  Created_By          INTEGER
                                             REFERENCES Users
                                               ON DELETE SET NULL,
  Account_Number      varchar,
  Partner_Name        varchar,
  Transaction_Type    varchar,
  Payment_Reference   varchar,
  Internal_Index      varchar,
  Transaction_Details TEXT,
  Amount              numeric,
  Amount_Currency     numeric,
  Is_Reconciled       tinyint(1) DEFAULT '0' NOT NULL,
  Amount_Residual     numeric,
  Created_At          datetime,
  Updated_At          datetime,
  Move_Id             INTEGER
    REFERENCES Accounts_Account_Moves
      ON DELETE CASCADE
);
