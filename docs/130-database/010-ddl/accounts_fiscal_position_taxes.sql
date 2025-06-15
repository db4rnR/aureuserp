CREATE TABLE Accounts_Fiscal_Position_Taxes
(
  Id                 INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Fiscal_Position_Id INTEGER NOT NULL
    REFERENCES Accounts_Fiscal_Positions
      ON DELETE CASCADE,
  Company_Id         INTEGER
                             REFERENCES Companies
                               ON DELETE SET NULL,
  Tax_Source_Id      INTEGER NOT NULL
    REFERENCES Accounts_Taxes
      ON DELETE RESTRICT,
  Tax_Destination_Id INTEGER
                             REFERENCES Accounts_Taxes
                               ON DELETE SET NULL,
  Creator_Id         INTEGER
                             REFERENCES Users
                               ON DELETE SET NULL,
  Created_At         datetime,
  Updated_At         datetime
);
