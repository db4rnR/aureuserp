CREATE TABLE Employees_Employee_Categories
(
  Employee_Id INTEGER
    REFERENCES Employees_Employees
      ON DELETE CASCADE,
  Category_Id INTEGER
    REFERENCES Employees_Categories
      ON DELETE CASCADE
);
