CREATE TABLE Inventories_Rules
(
  Id                       INTEGER                            NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                     INTEGER,
  Name                     varchar                            NOT NULL,
  Route_Sort               INTEGER    DEFAULT '0',
  Delay                    INTEGER    DEFAULT '0',
  Group_Propagation_Option varchar,
  Action                   varchar                            NOT NULL,
  Procure_Method           varchar    DEFAULT 'make_to_stock' NOT NULL,
  Auto                     varchar    DEFAULT 'manual'        NOT NULL,
  Push_Domain              varchar,
  Location_Dest_From_Rule  tinyint(1) DEFAULT '0',
  Propagate_Cancel         tinyint(1) DEFAULT '0',
  Propagate_Carrier        tinyint(1) DEFAULT '0',
  Source_Location_Id       INTEGER
                                                              REFERENCES Inventories_Locations
                                                                ON DELETE SET NULL,
  Destination_Location_Id  INTEGER                            NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Route_Id                 INTEGER                            NOT NULL
    REFERENCES Inventories_Routes
      ON DELETE CASCADE,
  Operation_Type_Id        INTEGER
                                                              REFERENCES Inventories_Operation_Types
                                                                ON DELETE SET NULL,
  Partner_Address_Id       INTEGER
                                                              REFERENCES Partners_Partners
                                                                ON DELETE SET NULL,
  Warehouse_Id             INTEGER
                                                              REFERENCES Inventories_Warehouses
                                                                ON DELETE SET NULL,
  Propagate_Warehouse_Id   INTEGER
                                                              REFERENCES Inventories_Warehouses
                                                                ON DELETE SET NULL,
  Company_Id               INTEGER                            NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id               INTEGER
                                                              REFERENCES Users
                                                                ON DELETE SET NULL,
  Deleted_At               datetime,
  Created_At               datetime,
  Updated_At               datetime
);

CREATE INDEX Inventories_Rules_Action_Index
  ON Inventories_Rules (Action);
