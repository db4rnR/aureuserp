CREATE TABLE Employees_Skills
(
  Id            INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort          INTEGER,
  Name          varchar NOT NULL,
  Skill_Type_Id INTEGER
    REFERENCES Employees_Skill_Types
      ON DELETE CASCADE,
  Creator_Id    INTEGER
                        REFERENCES Users
                          ON DELETE SET NULL,
  Created_At    datetime,
  Updated_At    datetime,
  Deleted_At    datetime
);

CREATE INDEX Employees_Skills_Creator_Id_Index
  ON Employees_Skills (Creator_Id);

CREATE INDEX Employees_Skills_Skill_Type_Id_Index
  ON Employees_Skills (Skill_Type_Id);
