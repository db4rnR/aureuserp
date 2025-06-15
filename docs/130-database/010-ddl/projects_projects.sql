CREATE TABLE Projects_Projects
(
  Id                      INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                    varchar                NOT NULL,
  Tasks_Label             varchar,
  Description             TEXT,
  Visibility              varchar,
  Color                   varchar,
  Sort                    INTEGER,
  Start_Date              date,
  End_Date                date,
  Allocated_Hours         numeric,
  Allow_Timesheets        tinyint(1) DEFAULT '0' NOT NULL,
  Allow_Milestones        tinyint(1) DEFAULT '0' NOT NULL,
  Allow_Task_Dependencies tinyint(1) DEFAULT '0' NOT NULL,
  Is_Active               tinyint(1) DEFAULT '1' NOT NULL,
  Stage_Id                INTEGER
    REFERENCES Projects_Project_Stages
      ON DELETE RESTRICT,
  Partner_Id              INTEGER
                                                 REFERENCES Partners_Partners
                                                   ON DELETE SET NULL,
  Company_Id              INTEGER
                                                 REFERENCES Companies
                                                   ON DELETE SET NULL,
  User_Id                 INTEGER
                                                 REFERENCES Users
                                                   ON DELETE SET NULL,
  Creator_Id              INTEGER
                                                 REFERENCES Users
                                                   ON DELETE SET NULL,
  Deleted_At              datetime,
  Created_At              datetime,
  Updated_At              datetime
);

CREATE INDEX Projects_Projects_Name_Index
  ON Projects_Projects (Name);

CREATE INDEX Projects_Projects_Sort_Index
  ON Projects_Projects (Sort);
