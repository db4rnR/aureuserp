CREATE TABLE Roles
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name       varchar NOT NULL,
  Guard_Name varchar NOT NULL,
  Created_At datetime,
  Updated_At datetime
);

CREATE UNIQUE INDEX Roles_Name_Guard_Name_Unique
  ON Roles (Name, Guard_Name);
