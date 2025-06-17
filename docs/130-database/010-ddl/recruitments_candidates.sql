CREATE TABLE Recruitments_Candidates
(
  Id                   INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Message_Bounced      INTEGER    DEFAULT '0',
  Company_Id           INTEGER
                               REFERENCES Companies
                                 ON DELETE SET NULL,
  Partner_Id           INTEGER
                               REFERENCES Partners_Partners
                                 ON DELETE SET NULL,
  Degree_Id            INTEGER
                               REFERENCES Recruitments_Degrees
                                 ON DELETE SET NULL,
  Manager_Id           INTEGER
                               REFERENCES Users
                                 ON DELETE SET NULL,
  Employee_Id          INTEGER
                               REFERENCES Employees_Employees
                                 ON DELETE SET NULL,
  Creator_Id           INTEGER
                               REFERENCES Users
                                 ON DELETE SET NULL,
  Email_Cc             varchar,
  Name                 varchar,
  Email_From           varchar,
  Phone                varchar,
  Linkedin_Profile     varchar,
  Priority             INTEGER    DEFAULT '0',
  Availability_Date    date,
  Candidate_Properties TEXT,
  Is_Active            tinyint(1) DEFAULT '1',
  Color                varchar,
  Deleted_At           datetime,
  Created_At           datetime,
  Updated_At           datetime
);
