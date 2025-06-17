CREATE TABLE Inventories_Lots
(
  Id              INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name            varchar NOT NULL,
  Description     TEXT,
  Reference       varchar,
  Properties      TEXT,
  Expiry_Reminded tinyint(1) DEFAULT '0',
  Expiration_Date datetime,
  Use_Date        datetime,
  Removal_Date    datetime,
  Alert_Date      datetime,
  Product_Id      INTEGER NOT NULL
    REFERENCES Products_Products
      ON DELETE RESTRICT,
  Uom_Id          INTEGER
                          REFERENCES Unit_Of_Measures
                            ON DELETE SET NULL,
  Location_Id     INTEGER
                          REFERENCES Inventories_Locations
                            ON DELETE SET NULL,
  Company_Id      INTEGER
                          REFERENCES Companies
                            ON DELETE SET NULL,
  Creator_Id      INTEGER
                          REFERENCES Users
                            ON DELETE SET NULL,
  Created_At      datetime,
  Updated_At      datetime
);

CREATE INDEX Inventories_Lots_Name_Index
  ON Inventories_Lots (Name);
