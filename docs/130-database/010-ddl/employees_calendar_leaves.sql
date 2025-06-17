CREATE TABLE Employees_Calendar_Leaves
(
  Id          INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name        varchar NOT NULL,
  Time_Type   varchar NOT NULL,
  Date_From   varchar NOT NULL,
  Date_To     varchar NOT NULL,
  Company_Id  INTEGER
                      REFERENCES Companies
                        ON DELETE SET NULL,
  Calendar_Id INTEGER
                      REFERENCES Employees_Calendars
                        ON DELETE SET NULL,
  Creator_Id  INTEGER
                      REFERENCES Users
                        ON DELETE SET NULL,
  Created_At  datetime,
  Updated_At  datetime
);
