CREATE TABLE Purchases_Requisitions
(
  Id          INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name        varchar NOT NULL,
  Type        varchar NOT NULL,
  State       varchar NOT NULL,
  Reference   varchar,
  Starts_At   date,
  Ends_At     date,
  Description TEXT,
  Currency_Id INTEGER NOT NULL
    REFERENCES Currencies
      ON DELETE RESTRICT,
  Partner_Id  INTEGER NOT NULL
    REFERENCES Partners_Partners
      ON DELETE RESTRICT,
  User_Id     INTEGER
                      REFERENCES Users
                        ON DELETE SET NULL,
  Company_Id  INTEGER NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id  INTEGER
                      REFERENCES Users
                        ON DELETE SET NULL,
  Deleted_At  datetime,
  Created_At  datetime,
  Updated_At  datetime
);
