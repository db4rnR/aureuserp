CREATE TABLE Products_Price_Rules
(
  Id          INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name        varchar NOT NULL,
  Sort        INTEGER,
  Currency_Id INTEGER NOT NULL
    REFERENCES Currencies
      ON DELETE RESTRICT,
  Company_Id  INTEGER
                      REFERENCES Companies
                        ON DELETE SET NULL,
  Creator_Id  INTEGER
                      REFERENCES Users
                        ON DELETE SET NULL,
  Deleted_At  datetime,
  Created_At  datetime,
  Updated_At  datetime
);
