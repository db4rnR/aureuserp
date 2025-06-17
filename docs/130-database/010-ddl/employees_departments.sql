CREATE TABLE Employees_Departments
(
  Id                   INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Company_Id           INTEGER
                               REFERENCES Companies
                                 ON DELETE SET NULL,
  Parent_Id            INTEGER
                               REFERENCES Employees_Departments
                                 ON DELETE SET NULL,
  Master_Department_Id INTEGER
                               REFERENCES Employees_Departments
                                 ON DELETE SET NULL,
  Creator_Id           INTEGER
                               REFERENCES Users
                                 ON DELETE SET NULL,
  Name                 varchar,
  Complete_Name        varchar,
  Parent_Path          varchar,
  Color                TEXT,
  Deleted_At           datetime,
  Created_At           datetime,
  Updated_At           datetime,
  Manager_Id           INTEGER
                               REFERENCES Employees_Employees
                                 ON DELETE SET NULL
);
