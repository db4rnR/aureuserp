CREATE TABLE Inventories_Package_Types
(
  Id                   INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                 INTEGER,
  Name                 varchar NOT NULL,
  Barcode              varchar,
  Height               numeric DEFAULT '0',
  Width                numeric DEFAULT '0',
  Length               numeric DEFAULT '0',
  Base_Weight          numeric DEFAULT '0',
  Max_Weight           numeric DEFAULT '0',
  Shipper_Package_Code varchar,
  Package_Carrier_Type varchar,
  Company_Id           INTEGER
                               REFERENCES Companies
                                 ON DELETE SET NULL,
  Creator_Id           INTEGER
                               REFERENCES Users
                                 ON DELETE SET NULL,
  Created_At           datetime,
  Updated_At           datetime
);

CREATE UNIQUE INDEX Inventories_Package_Types_Barcode_Unique
  ON Inventories_Package_Types (Barcode);
