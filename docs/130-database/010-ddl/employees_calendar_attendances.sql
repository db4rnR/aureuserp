CREATE TABLE Employees_Calendar_Attendances
(
  Id            INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort          INTEGER,
  Name          varchar NOT NULL,
  Day_Of_Week   varchar NOT NULL,
  Day_Period    varchar NOT NULL,
  Week_Type     varchar,
  Display_Type  varchar,
  Date_From     varchar,
  Date_To       varchar,
  Duration_Days varchar,
  Hour_From     varchar NOT NULL,
  Hour_To       varchar NOT NULL,
  Calendar_Id   INTEGER NOT NULL
    REFERENCES Employees_Calendars
      ON DELETE CASCADE,
  Creator_Id    INTEGER
                        REFERENCES Users
                          ON DELETE SET NULL,
  Created_At    datetime,
  Updated_At    datetime
);
