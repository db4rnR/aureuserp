CREATE TABLE Inventories_Product_Quantities
(
  Id                      INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Quantity                numeric    DEFAULT '0',
  Reserved_Quantity       numeric    DEFAULT '0' NOT NULL,
  Counted_Quantity        numeric    DEFAULT '0',
  Difference_Quantity     numeric    DEFAULT '0',
  Inventory_Diff_Quantity numeric    DEFAULT '0',
  Inventory_Quantity_Set  tinyint(1) DEFAULT '0' NOT NULL,
  Scheduled_At            date,
  Incoming_At             datetime               NOT NULL,
  Product_Id              INTEGER                NOT NULL
    REFERENCES Products_Products
      ON DELETE RESTRICT,
  Location_Id             INTEGER                NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Storage_Category_Id     INTEGER
                                                 REFERENCES Inventories_Storage_Categories
                                                   ON DELETE SET NULL,
  Lot_Id                  INTEGER
    REFERENCES Inventories_Lots
      ON DELETE RESTRICT,
  Package_Id              INTEGER
    REFERENCES Inventories_Packages
      ON DELETE RESTRICT,
  Partner_Id              INTEGER
                                                 REFERENCES Partners_Partners
                                                   ON DELETE SET NULL,
  User_Id                 INTEGER
                                                 REFERENCES Users
                                                   ON DELETE SET NULL,
  Company_Id              INTEGER
                                                 REFERENCES Companies
                                                   ON DELETE SET NULL,
  Creator_Id              INTEGER
                                                 REFERENCES Users
                                                   ON DELETE SET NULL,
  Created_At              datetime,
  Updated_At              datetime
);
