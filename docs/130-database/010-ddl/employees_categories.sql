CREATE TABLE Employees_Categories
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name       varchar NOT NULL,
  Color      varchar,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Created_At datetime,
  Updated_At datetime
);

CREATE UNIQUE INDEX Employees_Categories_Name_Unique
  ON Employees_Categories (Name);
