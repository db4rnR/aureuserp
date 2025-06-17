CREATE TABLE Inventories_Packages
(
  Id              INTEGER                      NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name            varchar                      NOT NULL,
  Package_Use     varchar DEFAULT 'disposable' NOT NULL,
  Pack_Date       date,
  Package_Type_Id INTEGER
                                               REFERENCES Inventories_Package_Types
                                                 ON DELETE SET NULL,
  Location_Id     INTEGER
                                               REFERENCES Inventories_Locations
                                                 ON DELETE SET NULL,
  Company_Id      INTEGER
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id      INTEGER
                                               REFERENCES Users
                                                 ON DELETE SET NULL,
  Created_At      datetime,
  Updated_At      datetime
);
