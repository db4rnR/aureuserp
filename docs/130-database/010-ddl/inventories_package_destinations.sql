CREATE TABLE Inventories_Package_Destinations
(
  Id                      INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Operation_Id            INTEGER
                                  REFERENCES Inventories_Operations
                                    ON DELETE SET NULL,
  Destination_Location_Id INTEGER
                                  REFERENCES Inventories_Locations
                                    ON DELETE SET NULL,
  Creator_Id              INTEGER
                                  REFERENCES Users
                                    ON DELETE SET NULL,
  Created_At              datetime,
  Updated_At              datetime
);
