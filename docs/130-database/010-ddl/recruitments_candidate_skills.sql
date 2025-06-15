CREATE TABLE Recruitments_Candidate_Skills
(
  Id             INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Candidate_Id   INTEGER NOT NULL
    REFERENCES Recruitments_Candidates
      ON DELETE CASCADE,
  Skill_Id       INTEGER NOT NULL
    REFERENCES Employees_Skills
      ON DELETE RESTRICT,
  Skill_Level_Id INTEGER NOT NULL
    REFERENCES Employees_Skill_Levels
      ON DELETE RESTRICT,
  Skill_Type_Id  INTEGER NOT NULL
    REFERENCES Employees_Skill_Types
      ON DELETE RESTRICT,
  Creator_Id     INTEGER
                         REFERENCES Users
                           ON DELETE SET NULL,
  Created_At     datetime,
  Updated_At     datetime
);
