CREATE TABLE Employees_Work_Locations
(
  Id              INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name            varchar NOT NULL,
  Location_Type   varchar NOT NULL,
  Location_Number varchar,
  Is_Active       tinyint(1) DEFAULT '0',
  Company_Id      INTEGER NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id      INTEGER
                          REFERENCES Users
                            ON DELETE SET NULL,
  Created_At      datetime,
  Updated_At      datetime,
  Deleted_At      datetime
);
