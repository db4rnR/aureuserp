CREATE TABLE Inventories_Operation_Types
(
  Id                                 INTEGER                         NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                               varchar                         NOT NULL,
  Type                               varchar                         NOT NULL,
  Sort                               INTEGER,
  Sequence_Code                      varchar                         NOT NULL,
  Reservation_Method                 varchar    DEFAULT 'at_confirm' NOT NULL,
  Reservation_Days_Before            INTEGER    DEFAULT '0',
  Reservation_Days_Before_Priority   INTEGER    DEFAULT '0',
  Product_Label_Format               varchar,
  Lot_Label_Format                   varchar,
  Package_Label_To_Print             varchar,
  Barcode                            varchar,
  Create_Backorder                   varchar    DEFAULT 'ask'        NOT NULL,
  Move_Type                          varchar    DEFAULT 'direct',
  Show_Entire_Packs                  tinyint(1) DEFAULT '0',
  Use_Create_Lots                    tinyint(1) DEFAULT '0',
  Use_Existing_Lots                  tinyint(1) DEFAULT '0',
  Print_Label                        tinyint(1) DEFAULT '0',
  Show_Operations                    tinyint(1) DEFAULT '0',
  Auto_Show_Reception_Report         tinyint(1) DEFAULT '0',
  Auto_Print_Delivery_Slip           tinyint(1) DEFAULT '0',
  Auto_Print_Return_Slip             tinyint(1) DEFAULT '0',
  Auto_Print_Product_Labels          tinyint(1) DEFAULT '0',
  Auto_Print_Lot_Labels              tinyint(1) DEFAULT '0',
  Auto_Print_Reception_Report        tinyint(1) DEFAULT '0',
  Auto_Print_Reception_Report_Labels tinyint(1) DEFAULT '0',
  Auto_Print_Packages                tinyint(1) DEFAULT '0',
  Auto_Print_Package_Label           tinyint(1) DEFAULT '0',
  Return_Operation_Type_Id           INTEGER
                                                                     REFERENCES Inventories_Operation_Types
                                                                       ON DELETE SET NULL,
  Source_Location_Id                 INTEGER                         NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Destination_Location_Id            INTEGER                         NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Warehouse_Id                       INTEGER
    REFERENCES Inventories_Warehouses
      ON DELETE CASCADE,
  Company_Id                         INTEGER                         NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id                         INTEGER
                                                                     REFERENCES Users
                                                                       ON DELETE SET NULL,
  Deleted_At                         datetime,
  Created_At                         datetime,
  Updated_At                         datetime
);
