CREATE TABLE Inventories_Locations
(
  Id                         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Position_X                 INTEGER    DEFAULT '0',
  Position_Y                 INTEGER    DEFAULT '0',
  Position_Z                 INTEGER    DEFAULT '0',
  Type                       varchar NOT NULL,
  Name                       varchar NOT NULL,
  Full_Name                  varchar,
  Description                varchar,
  Parent_Path                varchar,
  Barcode                    varchar,
  Removal_Strategy           varchar,
  Cyclic_Inventory_Frequency INTEGER    DEFAULT '0',
  Last_Inventory_Date        date,
  Next_Inventory_Date        date,
  Is_Scrap                   tinyint(1) DEFAULT '0',
  Is_Replenish               tinyint(1) DEFAULT '0',
  Is_Dock                    tinyint(1) DEFAULT '0',
  Parent_Id                  INTEGER
                                     REFERENCES Inventories_Locations
                                       ON DELETE SET NULL,
  Company_Id                 INTEGER
                                     REFERENCES Companies
                                       ON DELETE SET NULL,
  Storage_Category_Id        INTEGER
                                     REFERENCES Inventories_Storage_Categories
                                       ON DELETE SET NULL,
  Warehouse_Id               INTEGER
                                     REFERENCES Inventories_Warehouses
                                       ON DELETE SET NULL,
  Creator_Id                 INTEGER
                                     REFERENCES Users
                                       ON DELETE SET NULL,
  Deleted_At                 datetime,
  Created_At                 datetime,
  Updated_At                 datetime
);

CREATE UNIQUE INDEX Inventories_Locations_Company_Id_Barcode_Unique
  ON Inventories_Locations (Company_Id, Barcode);
