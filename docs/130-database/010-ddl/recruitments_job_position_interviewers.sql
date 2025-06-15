CREATE TABLE Recruitments_Job_Position_Interviewers
(
  Job_Position_Id INTEGER NOT NULL
    REFERENCES Employees_Job_Positions
      ON DELETE CASCADE,
  User_Id         INTEGER NOT NULL
    REFERENCES Users
      ON DELETE CASCADE
);
