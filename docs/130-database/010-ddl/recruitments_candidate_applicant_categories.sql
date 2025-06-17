CREATE TABLE Recruitments_Candidate_Applicant_Categories
(
  Candidate_Id INTEGER
    REFERENCES Recruitments_Candidates
      ON DELETE CASCADE,
  Category_Id  INTEGER
    REFERENCES Recruitments_Applicant_Categories
      ON DELETE CASCADE
);
