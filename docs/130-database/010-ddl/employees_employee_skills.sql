CREATE TABLE Employees_Employee_Skills
(
  Id             INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Employee_Id    INTEGER
    REFERENCES Employees_Employees
      ON DELETE CASCADE,
  Skill_Id       INTEGER
    REFERENCES Employees_Skills
      ON DELETE RESTRICT,
  Skill_Level_Id INTEGER
    REFERENCES Employees_Skill_Levels
      ON DELETE RESTRICT,
  Skill_Type_Id  INTEGER
    REFERENCES Employees_Skill_Types
      ON DELETE RESTRICT,
  Creator_Id     INTEGER
                         REFERENCES Users
                           ON DELETE SET NULL,
  Created_At     datetime,
  Updated_At     datetime,
  Deleted_At     datetime
);
