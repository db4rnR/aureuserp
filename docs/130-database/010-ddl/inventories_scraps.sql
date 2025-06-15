CREATE TABLE Inventories_Scraps
(
  Id                      INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                    varchar                NOT NULL,
  Origin                  varchar,
  State                   varchar,
  Qty                     numeric    DEFAULT '0' NOT NULL,
  Should_Replenish        tinyint(1) DEFAULT '0' NOT NULL,
  Closed_At               date,
  Product_Id              INTEGER                NOT NULL
    REFERENCES Products_Products
      ON DELETE RESTRICT,
  Uom_Id                  INTEGER                NOT NULL
    REFERENCES Unit_Of_Measures
      ON DELETE RESTRICT,
  Lot_Id                  INTEGER
                                                 REFERENCES Inventories_Lots
                                                   ON DELETE SET NULL,
  Package_Id              INTEGER
                                                 REFERENCES Inventories_Packages
                                                   ON DELETE SET NULL,
  Partner_Id              INTEGER
                                                 REFERENCES Partners_Partners
                                                   ON DELETE SET NULL,
  Operation_Id            INTEGER
                                                 REFERENCES Inventories_Operations
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
