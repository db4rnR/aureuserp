CREATE TABLE Inventories_Route_Packagings
(
  Route_Id     INTEGER NOT NULL
    REFERENCES Inventories_Routes
      ON DELETE CASCADE,
  Packaging_Id INTEGER NOT NULL
    REFERENCES Products_Packagings
      ON DELETE CASCADE
);
