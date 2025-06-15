CREATE TABLE Time_Off_Leaves
(
  Id                       INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  User_Id                  INTEGER
                                   REFERENCES Users
                                     ON DELETE SET NULL,
  Manager_Id               INTEGER
                                   REFERENCES Employees_Employees
                                     ON DELETE SET NULL,
  Holiday_Status_Id        INTEGER
    REFERENCES Time_Off_Leave_Types
      ON DELETE RESTRICT,
  Employee_Id              INTEGER NOT NULL
    REFERENCES Employees_Employees
      ON DELETE RESTRICT,
  Employee_Company_Id      INTEGER
                                   REFERENCES Companies
                                     ON DELETE SET NULL,
  Company_Id               INTEGER
                                   REFERENCES Companies
                                     ON DELETE SET NULL,
  Department_Id            INTEGER
                                   REFERENCES Employees_Departments
                                     ON DELETE SET NULL,
  Calendar_Id              INTEGER
                                   REFERENCES Employees_Calendars
                                     ON DELETE SET NULL,
  Meeting_Id               INTEGER,
  First_Approver_Id        INTEGER
                                   REFERENCES Employees_Employees
                                     ON DELETE SET NULL,
  Second_Approver_Id       INTEGER
                                   REFERENCES Employees_Employees
                                     ON DELETE SET NULL,
  Creator_Id               INTEGER
                                   REFERENCES Users
                                     ON DELETE SET NULL,
  Private_Name             varchar,
  State                    varchar,
  Duration_Display         varchar,
  Request_Date_From_Period varchar,
  Request_Date_From        datetime,
  Request_Date_To          datetime,
  Notes                    TEXT,
  Attachment               varchar,
  Request_Unit_Half        tinyint(1),
  Request_Unit_Hours       tinyint(1),
  Date_From                datetime,
  Date_To                  datetime,
  Number_Of_Days           numeric DEFAULT '0',
  Number_Of_Hours          numeric DEFAULT '0',
  Request_Hour_From        numeric DEFAULT '0',
  Request_Hour_To          numeric DEFAULT '0',
  Created_At               datetime,
  Updated_At               datetime
);
