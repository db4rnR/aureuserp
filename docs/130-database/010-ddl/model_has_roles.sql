CREATE TABLE Model_Has_Roles
(
  Role_Id    INTEGER NOT NULL
    REFERENCES Roles
      ON DELETE CASCADE,
  Model_Type varchar NOT NULL,
  Model_Id   INTEGER NOT NULL,
  PRIMARY KEY (Role_Id, Model_Id, Model_Type)
);

CREATE INDEX Model_Has_Roles_Model_Id_Model_Type_Index
  ON Model_Has_Roles (Model_Id, Model_Type);
