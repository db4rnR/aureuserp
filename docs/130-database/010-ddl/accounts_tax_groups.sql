CREATE TABLE Accounts_Tax_Groups
(
  Id                 INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort               INTEGER,
  Company_Id         INTEGER
    REFERENCES Companies
      ON DELETE RESTRICT,
  Country_Id         INTEGER
                             REFERENCES Countries
                               ON DELETE SET NULL,
  Creator_Id         INTEGER
                             REFERENCES Users
                               ON DELETE SET NULL,
  Name               varchar NOT NULL,
  Preceding_Subtotal varchar,
  Created_At         datetime,
  Updated_At         datetime
);
