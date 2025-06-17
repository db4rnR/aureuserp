CREATE TABLE Accounts_Tax_Partition_Lines
(
  Id                 INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Account_Id         INTEGER
                             REFERENCES Accounts_Accounts
                               ON DELETE SET NULL,
  Tax_Id             INTEGER
    REFERENCES Accounts_Taxes
      ON DELETE CASCADE,
  Company_Id         INTEGER
                             REFERENCES Companies
                               ON DELETE SET NULL,
  Creator_Id         INTEGER
                             REFERENCES Users
                               ON DELETE SET NULL,
  Sort               INTEGER,
  Repartition_Type   varchar NOT NULL,
  Document_Type      varchar NOT NULL,
  Use_In_Tax_Closing varchar,
  Factor             numeric DEFAULT '0',
  Factor_Percent     numeric DEFAULT '0',
  Created_At         datetime,
  Updated_At         datetime
);
