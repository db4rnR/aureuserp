CREATE TABLE Projects_Tasks
(
  Id                      INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Title                   varchar                NOT NULL,
  Description             TEXT,
  Color                   varchar,
  Priority                tinyint(1) DEFAULT '0' NOT NULL,
  State                   varchar                NOT NULL,
  Tags                    TEXT,
  Sort                    INTEGER,
  Is_Active               tinyint(1) DEFAULT '1' NOT NULL,
  Is_Recurring            tinyint(1) DEFAULT '0' NOT NULL,
  Deadline                datetime,
  Working_Hours_Open      numeric    DEFAULT '0' NOT NULL,
  Working_Hours_Close     numeric    DEFAULT '0' NOT NULL,
  Allocated_Hours         numeric    DEFAULT '0' NOT NULL,
  Remaining_Hours         numeric    DEFAULT '0' NOT NULL,
  Effective_Hours         numeric    DEFAULT '0' NOT NULL,
  Total_Hours_Spent       numeric    DEFAULT '0' NOT NULL,
  Overtime                numeric    DEFAULT '0' NOT NULL,
  Progress                numeric    DEFAULT '0' NOT NULL,
  Subtask_Effective_Hours numeric    DEFAULT '0' NOT NULL,
  Project_Id              INTEGER
                                                 REFERENCES Projects_Projects
                                                   ON DELETE SET NULL,
  Milestone_Id            INTEGER
                                                 REFERENCES Projects_Milestones
                                                   ON DELETE SET NULL,
  Stage_Id                INTEGER
    REFERENCES Projects_Task_Stages
      ON DELETE RESTRICT,
  Partner_Id              INTEGER
                                                 REFERENCES Partners_Partners
                                                   ON DELETE SET NULL,
  Parent_Id               INTEGER
                                                 REFERENCES Projects_Tasks
                                                   ON DELETE SET NULL,
  Company_Id              INTEGER
                                                 REFERENCES Companies
                                                   ON DELETE SET NULL,
  Creator_Id              INTEGER
                                                 REFERENCES Users
                                                   ON DELETE SET NULL,
  Deleted_At              datetime,
  Created_At              datetime,
  Updated_At              datetime
);

CREATE INDEX Projects_Tasks_Deadline_Index
  ON Projects_Tasks (Deadline);

CREATE INDEX Projects_Tasks_Priority_Index
  ON Projects_Tasks (Priority);

CREATE INDEX Projects_Tasks_State_Index
  ON Projects_Tasks (State);

CREATE INDEX Projects_Tasks_Title_Index
  ON Projects_Tasks (Title);
