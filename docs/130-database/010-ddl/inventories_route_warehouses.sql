CREATE TABLE Inventories_Route_Warehouses
(
  Warehouse_Id INTEGER NOT NULL
    REFERENCES Inventories_Warehouses
      ON DELETE CASCADE,
  Route_Id     INTEGER NOT NULL
    REFERENCES Inventories_Routes
      ON DELETE CASCADE
);
