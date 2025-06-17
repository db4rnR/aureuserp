CREATE TABLE Products_Packagings
(
  Id              INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name            varchar NOT NULL,
  Barcode         varchar,
  Qty             numeric,
  Sort            INTEGER,
  Product_Id      INTEGER NOT NULL
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Creator_Id      INTEGER
                          REFERENCES Users
                            ON DELETE SET NULL,
  Company_Id      INTEGER
                          REFERENCES Companies
                            ON DELETE SET NULL,
  Created_At      datetime,
  Updated_At      datetime,
  Package_Type_Id INTEGER
                          REFERENCES Inventories_Package_Types
                            ON DELETE SET NULL
);
