CREATE TABLE Inventories_Category_Routes
(
  Category_Id INTEGER NOT NULL
    REFERENCES Products_Categories
      ON DELETE CASCADE,
  Route_Id    INTEGER NOT NULL
    REFERENCES Inventories_Routes
      ON DELETE CASCADE
);
