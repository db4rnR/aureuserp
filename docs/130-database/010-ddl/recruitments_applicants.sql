CREATE TABLE Recruitments_Applicants
(
  Id                      INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Source_Id               INTEGER
                                                 REFERENCES Utm_Sources
                                                   ON DELETE SET NULL,
  Medium_Id               INTEGER
                                                 REFERENCES Utm_Mediums
                                                   ON DELETE SET NULL,
  Candidate_Id            INTEGER                NOT NULL
    REFERENCES Recruitments_Candidates
      ON DELETE RESTRICT,
  Stage_Id                INTEGER
    REFERENCES Recruitments_Stages
      ON DELETE RESTRICT,
  Last_Stage_Id           INTEGER
                                                 REFERENCES Recruitments_Stages
                                                   ON DELETE SET NULL,
  Company_Id              INTEGER
                                                 REFERENCES Companies
                                                   ON DELETE SET NULL,
  Recruiter_Id            INTEGER
                                                 REFERENCES Users
                                                   ON DELETE SET NULL,
  Job_Id                  INTEGER
                                                 REFERENCES Employees_Job_Positions
                                                   ON DELETE SET NULL,
  Department_Id           INTEGER
                                                 REFERENCES Employees_Departments
                                                   ON DELETE SET NULL,
  Refuse_Reason_Id        INTEGER
                                                 REFERENCES Recruitments_Refuse_Reasons
                                                   ON DELETE SET NULL,
  Creator_Id              INTEGER
                                                 REFERENCES Users
                                                   ON DELETE SET NULL,
  Email_Cc                varchar,
  Priority                varchar    DEFAULT '0',
  Salary_Proposed_Extra   varchar,
  Salary_Expected_Extra   varchar,
  Applicant_Properties    TEXT,
  Applicant_Notes         TEXT,
  Is_Active               tinyint(1) DEFAULT '0' NOT NULL,
  State                   varchar,
  Create_Date             datetime,
  Date_Closed             datetime,
  Date_Opened             datetime,
  Date_Last_Stage_Updated datetime,
  Refuse_Date             datetime,
  Probability             numeric    DEFAULT '0',
  Salary_Proposed         numeric    DEFAULT '0',
  Salary_Expected         numeric    DEFAULT '0',
  Delay_Close             numeric    DEFAULT '0',
  Deleted_At              datetime,
  Created_At              datetime,
  Updated_At              datetime
);
