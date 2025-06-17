CREATE TABLE Employees_Calendars
(
  Id                       INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                     varchar                NOT NULL,
  Timezone                 varchar                NOT NULL,
  Hours_Per_Day            float,
  Is_Active                tinyint(1) DEFAULT '0' NOT NULL,
  Two_Weeks_Calendar       tinyint(1) DEFAULT '0',
  Flexible_Hours           tinyint(1) DEFAULT '0',
  Full_Time_Required_Hours float,
  Creator_Id               INTEGER
                                                  REFERENCES Users
                                                    ON DELETE SET NULL,
  Company_Id               INTEGER
                                                  REFERENCES Companies
                                                    ON DELETE SET NULL,
  Deleted_At               datetime,
  Created_At               datetime,
  Updated_At               datetime
);
