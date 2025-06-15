CREATE TABLE Employees_Skill_Types
(
  Id         INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name       varchar                NOT NULL,
  Color      varchar,
  Is_Active  tinyint(1) DEFAULT '0' NOT NULL,
  Creator_Id INTEGER
                                    REFERENCES Users
                                      ON DELETE SET NULL,
  Created_At datetime,
  Updated_At datetime,
  Deleted_At datetime
);
