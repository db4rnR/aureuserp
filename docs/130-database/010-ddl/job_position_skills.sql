CREATE TABLE Job_Position_Skills
(
  Job_Position_Id INTEGER NOT NULL
    REFERENCES Employees_Job_Positions
      ON DELETE CASCADE,
  Skill_Id        INTEGER NOT NULL
    REFERENCES Employees_Skills
      ON DELETE CASCADE
);
