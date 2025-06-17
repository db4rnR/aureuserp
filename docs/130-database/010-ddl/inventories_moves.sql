CREATE TABLE Inventories_Moves
(
  Id                      INTEGER                            NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                    varchar                            NOT NULL,
  State                   varchar,
  Origin                  varchar,
  Procure_Method          varchar    DEFAULT 'make_to_stock' NOT NULL,
  Reference               varchar,
  Description_Picking     TEXT,
  Next_Serial             varchar,
  Next_Serial_Count       INTEGER,
  Is_Favorite             tinyint(1) DEFAULT '0'             NOT NULL,
  Product_Qty             numeric    DEFAULT '0',
  Product_Uom_Qty         numeric    DEFAULT '0',
  Quantity                numeric    DEFAULT '0',
  Is_Picked               tinyint(1) DEFAULT '0'             NOT NULL,
  Is_Scraped              tinyint(1) DEFAULT '0'             NOT NULL,
  Is_Inventory            tinyint(1) DEFAULT '0'             NOT NULL,
  Reservation_Date        date,
  Scheduled_At            datetime                           NOT NULL,
  Deadline                datetime,
  Alert_Date              datetime,
  Operation_Id            INTEGER
                                                             REFERENCES Inventories_Operations
                                                               ON DELETE SET NULL,
  Product_Id              INTEGER                            NOT NULL
    REFERENCES Products_Products
      ON DELETE RESTRICT,
  Uom_Id                  INTEGER                            NOT NULL
    REFERENCES Unit_Of_Measures
      ON DELETE RESTRICT,
  Source_Location_Id      INTEGER                            NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Destination_Location_Id INTEGER                            NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Final_Location_Id       INTEGER
                                                             REFERENCES Inventories_Locations
                                                               ON DELETE SET NULL,
  Partner_Id              INTEGER
                                                             REFERENCES Partners_Partners
                                                               ON DELETE SET NULL,
  Scrap_Id                INTEGER
                                                             REFERENCES Inventories_Scraps
                                                               ON DELETE SET NULL,
  Rule_Id                 INTEGER
                                                             REFERENCES Inventories_Rules
                                                               ON DELETE SET NULL,
  Operation_Type_Id       INTEGER
                                                             REFERENCES Inventories_Operation_Types
                                                               ON DELETE SET NULL,
  Origin_Returned_Move_Id INTEGER
                                                             REFERENCES Inventories_Moves
                                                               ON DELETE SET NULL,
  Restrict_Partner_Id     INTEGER
                                                             REFERENCES Partners_Partners
                                                               ON DELETE SET NULL,
  Warehouse_Id            INTEGER
                                                             REFERENCES Inventories_Warehouses
                                                               ON DELETE SET NULL,
  Package_Level_Id        INTEGER
                                                             REFERENCES Inventories_Package_Levels
                                                               ON DELETE SET NULL,
  Product_Packaging_Id    INTEGER
                                                             REFERENCES Products_Packagings
                                                               ON DELETE SET NULL,
  Company_Id              INTEGER                            NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id              INTEGER
                                                             REFERENCES Users
                                                               ON DELETE SET NULL,
  Created_At              datetime,
  Updated_At              datetime,
  Is_Refund               tinyint(1) DEFAULT '0'             NOT NULL,
  Purchase_Order_Line_Id  INTEGER
    REFERENCES Purchases_Order_Lines
      ON DELETE RESTRICT,
  Sale_Order_Line_Id      INTEGER
    REFERENCES Sales_Order_Lines
      ON DELETE RESTRICT
);
