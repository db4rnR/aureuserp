CREATE TABLE Inventories_Storage_Category_Capacities
(
  Id                  INTEGER             NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Qty                 numeric DEFAULT '0' NOT NULL,
  Product_Id          INTEGER
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Storage_Category_Id INTEGER             NOT NULL
    REFERENCES Inventories_Storage_Categories
      ON DELETE CASCADE,
  Package_Type_Id     INTEGER
    REFERENCES Inventories_Package_Types
      ON DELETE CASCADE,
  Creator_Id          INTEGER
                                          REFERENCES Users
                                            ON DELETE SET NULL,
  Created_At          datetime,
  Updated_At          datetime
);

CREATE UNIQUE INDEX Unique_Package_Type_Storage_Category
  ON Inventories_Storage_Category_Capacities (Package_Type_Id, Storage_Category_Id);

CREATE UNIQUE INDEX Unique_Product_Storage_Category
  ON Inventories_Storage_Category_Capacities (Product_Id, Storage_Category_Id);
