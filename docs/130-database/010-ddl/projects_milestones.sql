CREATE TABLE Projects_Milestones
(
  Id           INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name         varchar                NOT NULL,
  Deadline     datetime,
  Is_Completed tinyint(1) DEFAULT '0' NOT NULL,
  Completed_At datetime,
  Project_Id   INTEGER                NOT NULL
    REFERENCES Projects_Projects
      ON DELETE CASCADE,
  Creator_Id   INTEGER
                                      REFERENCES Users
                                        ON DELETE SET NULL,
  Created_At   datetime,
  Updated_At   datetime
);

CREATE INDEX Projects_Milestones_Completed_At_Index
  ON Projects_Milestones (Completed_At);

CREATE INDEX Projects_Milestones_Deadline_Index
  ON Projects_Milestones (Deadline);

CREATE INDEX Projects_Milestones_Name_Index
  ON Projects_Milestones (Name);
