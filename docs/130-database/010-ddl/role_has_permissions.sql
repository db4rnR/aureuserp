CREATE TABLE Role_Has_Permissions
(
  Permission_Id INTEGER NOT NULL
    REFERENCES Permissions
      ON DELETE CASCADE,
  Role_Id       INTEGER NOT NULL
    REFERENCES Roles
      ON DELETE CASCADE,
  PRIMARY KEY (Permission_Id, Role_Id)
);
