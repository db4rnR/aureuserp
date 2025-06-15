CREATE TABLE Employees_Skill_Levels
(
  Id            INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name          varchar NOT NULL,
  Level         INTEGER,
  Default_Level tinyint(1),
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

CREATE INDEX Employees_Skill_Levels_Creator_Id_Index
  ON Employees_Skill_Levels (Creator_Id);

CREATE INDEX Employees_Skill_Levels_Skill_Type_Id_Index
  ON Employees_Skill_Levels (Skill_Type_Id);
