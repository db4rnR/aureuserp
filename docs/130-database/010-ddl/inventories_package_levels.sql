CREATE TABLE Inventories_Package_Levels
(
  Id                      INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Package_Id              INTEGER NOT NULL
    REFERENCES Inventories_Packages
      ON DELETE RESTRICT,
  Operation_Id            INTEGER
                                  REFERENCES Inventories_Operations
                                    ON DELETE SET NULL,
  Destination_Location_Id INTEGER
                                  REFERENCES Inventories_Locations
                                    ON DELETE SET NULL,
  Company_Id              INTEGER NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id              INTEGER
                                  REFERENCES Users
                                    ON DELETE SET NULL,
  Created_At              datetime,
  Updated_At              datetime
);
