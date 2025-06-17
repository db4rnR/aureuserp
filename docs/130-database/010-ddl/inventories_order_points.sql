CREATE TABLE Inventories_Order_Points
(
  Id                  INTEGER             NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                varchar             NOT NULL,
  Trigger             varchar             NOT NULL,
  Snoozed_Until       date,
  Product_Min_Qty     numeric DEFAULT '0' NOT NULL,
  Product_Max_Qty     numeric DEFAULT '0' NOT NULL,
  Qty_Multiple        numeric DEFAULT '0' NOT NULL,
  Qty_To_Order_Manual numeric DEFAULT '0',
  Product_Id          INTEGER             NOT NULL
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Product_Category_Id INTEGER
                                          REFERENCES Products_Categories
                                            ON DELETE SET NULL,
  Warehouse_Id        INTEGER             NOT NULL
    REFERENCES Inventories_Warehouses
      ON DELETE CASCADE,
  Location_Id         INTEGER             NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE CASCADE,
  Route_Id            INTEGER
                                          REFERENCES Inventories_Routes
                                            ON DELETE SET NULL,
  Company_Id          INTEGER             NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id          INTEGER
                                          REFERENCES Users
                                            ON DELETE SET NULL,
  Deleted_At          datetime,
  Created_At          datetime,
  Updated_At          datetime
);
