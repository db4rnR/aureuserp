CREATE TABLE Inventories_Warehouse_Resupplies
(
  Supplied_Warehouse_Id INTEGER NOT NULL
    REFERENCES Inventories_Warehouses
      ON DELETE CASCADE,
  Supplier_Warehouse_Id INTEGER NOT NULL
    REFERENCES Inventories_Warehouses
      ON DELETE CASCADE
);
