CREATE TABLE Recruitments_Stages_Jobs
(
  Stage_Id INTEGER
    REFERENCES Recruitments_Stages
      ON DELETE CASCADE,
  Job_Id   INTEGER
    REFERENCES Employees_Job_Positions
      ON DELETE CASCADE
);
