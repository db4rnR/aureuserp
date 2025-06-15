CREATE TABLE Recruitments_Applicant_Interviewers
(
  Applicant_Id   INTEGER NOT NULL
    REFERENCES Recruitments_Applicants
      ON DELETE CASCADE,
  Interviewer_Id INTEGER NOT NULL
    REFERENCES Users
      ON DELETE CASCADE
);
