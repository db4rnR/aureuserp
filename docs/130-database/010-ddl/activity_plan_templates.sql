CREATE TABLE Activity_Plan_Templates
(
  Id               INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort             INTEGER,
  Plan_Id          INTEGER NOT NULL
    REFERENCES Activity_Plans
      ON DELETE CASCADE,
  Activity_Type_Id INTEGER NOT NULL
    REFERENCES Activity_Types
      ON DELETE RESTRICT,
  Responsible_Id   INTEGER
                           REFERENCES Users
                             ON DELETE SET NULL,
  Creator_Id       INTEGER
                           REFERENCES Users
                             ON DELETE SET NULL,
  Delay_Count      INTEGER,
  Delay_Unit       varchar NOT NULL,
  Delay_From       varchar NOT NULL,
  Summary          TEXT,
  Responsible_Type varchar NOT NULL,
  Note             TEXT,
  Created_At       datetime,
  Updated_At       datetime
);
