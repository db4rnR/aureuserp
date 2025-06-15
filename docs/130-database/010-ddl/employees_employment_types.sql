CREATE TABLE Employees_Employment_Types
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort       INTEGER,
  Name       varchar NOT NULL,
  Code       varchar,
  Country_Id INTEGER
                     REFERENCES Countries
                       ON DELETE SET NULL,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Created_At datetime,
  Updated_At datetime
);
