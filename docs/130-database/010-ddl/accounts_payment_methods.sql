CREATE TABLE Accounts_Payment_Methods
(
  Id           INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Code         varchar NOT NULL,
  Payment_Type varchar NOT NULL,
  Name         varchar NOT NULL,
  Created_By   INTEGER
    REFERENCES Users,
  Created_At   datetime,
  Updated_At   datetime
);
