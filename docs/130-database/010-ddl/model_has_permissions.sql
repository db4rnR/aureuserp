CREATE TABLE Model_Has_Permissions
(
  Permission_Id INTEGER NOT NULL
    REFERENCES Permissions
      ON DELETE CASCADE,
  Model_Type    varchar NOT NULL,
  Model_Id      INTEGER NOT NULL,
  PRIMARY KEY (Permission_Id, Model_Id, Model_Type)
);

CREATE INDEX Model_Has_Permissions_Model_Id_Model_Type_Index
  ON Model_Has_Permissions (Model_Id, Model_Type);
