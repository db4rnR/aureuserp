CREATE TABLE Activity_Plans
(
  Id            INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Plugin        varchar,
  Name          varchar NOT NULL,
  Is_Active     tinyint(1) DEFAULT '0',
  Creator_Id    INTEGER
                        REFERENCES Users
                          ON DELETE SET NULL,
  Company_Id    INTEGER
                        REFERENCES Companies
                          ON DELETE SET NULL,
  Deleted_At    datetime,
  Created_At    datetime,
  Updated_At    datetime,
  Department_Id INTEGER
                        REFERENCES Employees_Departments
                          ON DELETE SET NULL
);
