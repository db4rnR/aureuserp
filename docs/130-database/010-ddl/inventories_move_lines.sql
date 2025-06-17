CREATE TABLE Inventories_Move_Lines
(
  Id                      INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Lot_Name                varchar,
  State                   varchar,
  Reference               varchar,
  Picking_Description     varchar,
  Qty                     numeric    DEFAULT '0',
  Uom_Qty                 numeric    DEFAULT '0',
  Is_Picked               tinyint(1) DEFAULT '0' NOT NULL,
  Scheduled_At            datetime               NOT NULL,
  Move_Id                 INTEGER
                                                 REFERENCES Inventories_Moves
                                                   ON DELETE SET NULL,
  Operation_Id            INTEGER
                                                 REFERENCES Inventories_Operations
                                                   ON DELETE SET NULL,
  Product_Id              INTEGER                NOT NULL
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Uom_Id                  INTEGER                NOT NULL
    REFERENCES Unit_Of_Measures
      ON DELETE RESTRICT,
  Package_Id              INTEGER
    REFERENCES Inventories_Packages
      ON DELETE RESTRICT,
  Result_Package_Id       INTEGER
    REFERENCES Inventories_Packages
      ON DELETE RESTRICT,
  Package_Level_Id        INTEGER
                                                 REFERENCES Inventories_Package_Levels
                                                   ON DELETE SET NULL,
  Lot_Id                  INTEGER
                                                 REFERENCES Inventories_Lots
                                                   ON DELETE SET NULL,
  Partner_Id              INTEGER
                                                 REFERENCES Partners_Partners
                                                   ON DELETE SET NULL,
  Source_Location_Id      INTEGER                NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Destination_Location_Id INTEGER                NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Company_Id              INTEGER                NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id              INTEGER
                                                 REFERENCES Users
                                                   ON DELETE SET NULL,
  Created_At              datetime,
  Updated_At              datetime
);
