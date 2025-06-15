CREATE TABLE Inventories_Product_Quantity_Relocations
(
  Id                      INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Description             TEXT,
  Destination_Location_Id INTEGER
                                  REFERENCES Inventories_Locations
                                    ON DELETE SET NULL,
  Destination_Package_Id  INTEGER NOT NULL
    REFERENCES Inventories_Packages
      ON DELETE RESTRICT,
  Creator_Id              INTEGER
                                  REFERENCES Users
                                    ON DELETE SET NULL,
  Created_At              datetime,
  Updated_At              datetime
);
