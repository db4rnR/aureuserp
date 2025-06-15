CREATE TABLE Projects_Task_Stages
(
  Id           INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name         varchar                NOT NULL,
  Is_Active    tinyint(1) DEFAULT '1' NOT NULL,
  Is_Collapsed tinyint(1) DEFAULT '0' NOT NULL,
  Sort         INTEGER,
  Project_Id   INTEGER                NOT NULL
    REFERENCES Projects_Projects
      ON DELETE CASCADE,
  Company_Id   INTEGER
                                      REFERENCES Companies
                                        ON DELETE SET NULL,
  User_Id      INTEGER
                                      REFERENCES Users
                                        ON DELETE SET NULL,
  Creator_Id   INTEGER
                                      REFERENCES Users
                                        ON DELETE SET NULL,
  Deleted_At   datetime,
  Created_At   datetime,
  Updated_At   datetime
);
