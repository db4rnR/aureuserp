CREATE TABLE Employees_Employee_Resume_Line_Types
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort       INTEGER NOT NULL,
  Name       varchar NOT NULL,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Created_At datetime,
  Updated_At datetime
);
