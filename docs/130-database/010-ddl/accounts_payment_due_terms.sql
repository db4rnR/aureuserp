CREATE TABLE Accounts_Payment_Due_Terms
(
  Id              INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Nb_Days         INTEGER,
  Payment_Id      INTEGER
    REFERENCES Accounts_Payment_Terms
      ON DELETE CASCADE,
  Creator_Id      INTEGER
                          REFERENCES Users
                            ON DELETE SET NULL,
  Value           varchar NOT NULL,
  Delay_Type      varchar NOT NULL,
  Days_Next_Month varchar,
  Value_Amount    numeric DEFAULT '0',
  Created_At      datetime,
  Updated_At      datetime
);
