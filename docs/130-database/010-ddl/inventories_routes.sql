CREATE TABLE Inventories_Routes
(
  Id                          INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                        INTEGER,
  Name                        varchar NOT NULL,
  Product_Selectable          tinyint(1) DEFAULT '0',
  Product_Category_Selectable tinyint(1) DEFAULT '0',
  Warehouse_Selectable        tinyint(1) DEFAULT '0',
  Packaging_Selectable        tinyint(1) DEFAULT '0',
  Supplied_Warehouse_Id       INTEGER
                                      REFERENCES Inventories_Warehouses
                                        ON DELETE SET NULL,
  Supplier_Warehouse_Id       INTEGER
                                      REFERENCES Inventories_Warehouses
                                        ON DELETE SET NULL,
  Company_Id                  INTEGER
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id                  INTEGER
                                      REFERENCES Users
                                        ON DELETE SET NULL,
  Deleted_At                  datetime,
  Created_At                  datetime,
  Updated_At                  datetime
);
