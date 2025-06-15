CREATE TABLE Accounts_Accounts
(
  Id           INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Currency_Id  INTEGER
                       REFERENCES Currencies
                         ON DELETE SET NULL,
  Creator_Id   INTEGER
                       REFERENCES Users
                         ON DELETE SET NULL,
  Account_Type varchar NOT NULL,
  Name         varchar NOT NULL,
  Code         varchar,
  Note         varchar,
  Deprecated   tinyint(1),
  Reconcile    tinyint(1),
  Non_Trade    tinyint(1),
  Created_At   datetime,
  Updated_At   datetime
);
