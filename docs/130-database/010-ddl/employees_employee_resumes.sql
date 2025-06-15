CREATE TABLE Employees_Employee_Resumes
(
  Id                           INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Employee_Id                  INTEGER NOT NULL
    REFERENCES Employees_Employees
      ON DELETE CASCADE,
  Employee_Resume_Line_Type_Id INTEGER
                                       REFERENCES Employees_Employee_Resume_Line_Types
                                         ON DELETE SET NULL,
  Creator_Id                   INTEGER
                                       REFERENCES Users
                                         ON DELETE SET NULL,
  User_Id                      INTEGER
                                       REFERENCES Users
                                         ON DELETE SET NULL,
  Display_Type                 varchar NOT NULL,
  Start_Date                   date    NOT NULL,
  End_Date                     date,
  Name                         varchar NOT NULL,
  Description                  TEXT,
  Created_At                   datetime,
  Updated_At                   datetime
);
