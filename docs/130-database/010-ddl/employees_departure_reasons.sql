CREATE TABLE Employees_Departure_Reasons
(
  Id          INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort        INTEGER,
  Reason_Code INTEGER,
  Name        varchar NOT NULL,
  Creator_Id  INTEGER
                      REFERENCES Users
                        ON DELETE SET NULL,
  Created_At  datetime,
  Updated_At  datetime
);
