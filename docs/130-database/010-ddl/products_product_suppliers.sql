CREATE TABLE Products_Product_Suppliers
(
  Id           INTEGER             NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort         INTEGER,
  Delay        INTEGER DEFAULT '0' NOT NULL,
  Product_Name varchar,
  Product_Code varchar,
  Starts_At    date,
  Ends_At      date,
  Min_Qty      numeric DEFAULT '0' NOT NULL,
  Price        numeric DEFAULT '0' NOT NULL,
  Discount     numeric DEFAULT '0' NOT NULL,
  Product_Id   INTEGER
                                   REFERENCES Products_Products
                                     ON DELETE SET NULL,
  Partner_Id   INTEGER             NOT NULL
    REFERENCES Partners_Partners
      ON DELETE CASCADE,
  Currency_Id  INTEGER             NOT NULL
    REFERENCES Currencies
      ON DELETE RESTRICT,
  Company_Id   INTEGER
                                   REFERENCES Companies
                                     ON DELETE SET NULL,
  Creator_Id   INTEGER
                                   REFERENCES Users
                                     ON DELETE SET NULL,
  Created_At   datetime,
  Updated_At   datetime
);
