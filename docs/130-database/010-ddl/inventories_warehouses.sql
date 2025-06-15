CREATE TABLE Inventories_Warehouses
(
  Id                       INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                     varchar NOT NULL,
  Code                     varchar,
  Sort                     INTEGER,
  Reception_Steps          varchar NOT NULL,
  Delivery_Steps           varchar NOT NULL,
  Company_Id               INTEGER NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Partner_Address_Id       INTEGER
                                   REFERENCES Partners_Partners
                                     ON DELETE SET NULL,
  Creator_Id               INTEGER
                                   REFERENCES Users
                                     ON DELETE SET NULL,
  Deleted_At               datetime,
  Created_At               datetime,
  Updated_At               datetime,
  View_Location_Id         INTEGER NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Lot_Stock_Location_Id    INTEGER NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Input_Stock_Location_Id  INTEGER
                                   REFERENCES Inventories_Locations
                                     ON DELETE SET NULL,
  Qc_Stock_Location_Id     INTEGER
                                   REFERENCES Inventories_Locations
                                     ON DELETE SET NULL,
  Output_Stock_Location_Id INTEGER
                                   REFERENCES Inventories_Locations
                                     ON DELETE SET NULL,
  Pack_Stock_Location_Id   INTEGER
                                   REFERENCES Inventories_Locations
                                     ON DELETE SET NULL,
  Mto_Pull_Id              INTEGER
                                   REFERENCES Inventories_Rules
                                     ON DELETE SET NULL,
  Buy_Pull_Id              INTEGER
                                   REFERENCES Inventories_Rules
                                     ON DELETE SET NULL,
  Pick_Type_Id             INTEGER
                                   REFERENCES Inventories_Operation_Types
                                     ON DELETE SET NULL,
  Pack_Type_Id             INTEGER
                                   REFERENCES Inventories_Operation_Types
                                     ON DELETE SET NULL,
  Out_Type_Id              INTEGER
                                   REFERENCES Inventories_Operation_Types
                                     ON DELETE SET NULL,
  In_Type_Id               INTEGER
                                   REFERENCES Inventories_Operation_Types
                                     ON DELETE SET NULL,
  Internal_Type_Id         INTEGER
                                   REFERENCES Inventories_Operation_Types
                                     ON DELETE SET NULL,
  Qc_Type_Id               INTEGER
                                   REFERENCES Inventories_Operation_Types
                                     ON DELETE SET NULL,
  Store_Type_Id            INTEGER
                                   REFERENCES Inventories_Operation_Types
                                     ON DELETE SET NULL,
  Xdock_Type_Id            INTEGER
                                   REFERENCES Inventories_Operation_Types
                                     ON DELETE SET NULL,
  Crossdock_Route_Id       INTEGER
    REFERENCES Inventories_Routes
      ON DELETE RESTRICT,
  Reception_Route_Id       INTEGER
    REFERENCES Inventories_Routes
      ON DELETE RESTRICT,
  Delivery_Route_Id        INTEGER
    REFERENCES Inventories_Routes
      ON DELETE RESTRICT
);

CREATE UNIQUE INDEX Inventories_Warehouses_Company_Id_Code_Unique
  ON Inventories_Warehouses (Company_Id, Code);

CREATE UNIQUE INDEX Inventories_Warehouses_Company_Id_Name_Unique
  ON Inventories_Warehouses (Company_Id, Name);

CREATE INDEX Inventories_Warehouses_Name_Index
  ON Inventories_Warehouses (Name);
