CREATE TABLE Employees_Job_Positions
(
  Id                   INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                 INTEGER,
  Expected_Employees   INTEGER,
  No_Of_Employee       INTEGER,
  No_Of_Recruitment    INTEGER,
  Department_Id        INTEGER
                                              REFERENCES Employees_Departments
                                                ON DELETE SET NULL,
  Company_Id           INTEGER
                                              REFERENCES Companies
                                                ON DELETE SET NULL,
  Creator_Id           INTEGER
                                              REFERENCES Users
                                                ON DELETE SET NULL,
  Employment_Type_Id   INTEGER
                                              REFERENCES Employees_Employment_Types
                                                ON DELETE SET NULL,
  Name                 varchar                NOT NULL,
  Description          TEXT,
  Requirements         TEXT,
  Is_Active            tinyint(1) DEFAULT '0' NOT NULL,
  Deleted_At           datetime,
  Created_At           datetime,
  Updated_At           datetime,
  Address_Id           INTEGER
                                              REFERENCES Partners_Partners
                                                ON DELETE SET NULL,
  Manager_Id           INTEGER
                                              REFERENCES Employees_Employees
                                                ON DELETE SET NULL,
  Industry_Id          INTEGER
                                              REFERENCES Partners_Industries
                                                ON DELETE SET NULL,
  Recruiter_Id         INTEGER
                                              REFERENCES Users
                                                ON DELETE SET NULL,
  No_Of_Hired_Employee INTEGER,
  Date_From            datetime,
  Date_To              datetime
);
