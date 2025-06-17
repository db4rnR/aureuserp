CREATE TABLE Time_Off_Leave_Mandatory_Days
(
  Id         INTEGER  NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Company_Id INTEGER
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id INTEGER
                      REFERENCES Users
                        ON DELETE SET NULL,
  Color      varchar,
  Name       varchar  NOT NULL,
  Start_Date datetime NOT NULL,
  End_Date   datetime NOT NULL,
  Created_At datetime,
  Updated_At datetime
);
