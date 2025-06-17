CREATE TABLE Plugin_Dependencies
(
  Plugin_Id     INTEGER NOT NULL
    REFERENCES Plugins
      ON DELETE CASCADE,
  Dependency_Id INTEGER NOT NULL
    REFERENCES Plugins
      ON DELETE CASCADE
);

CREATE UNIQUE INDEX Plugin_Dependencies_Plugin_Id_Dependency_Id_Unique
  ON Plugin_Dependencies (Plugin_Id, Dependency_Id);
