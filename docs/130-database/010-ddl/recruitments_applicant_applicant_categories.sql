CREATE TABLE Recruitments_Applicant_Applicant_Categories
(
  Applicant_Id INTEGER
    REFERENCES Recruitments_Applicants
      ON DELETE CASCADE,
  Category_Id  INTEGER
    REFERENCES Recruitments_Applicant_Categories
      ON DELETE CASCADE
);
