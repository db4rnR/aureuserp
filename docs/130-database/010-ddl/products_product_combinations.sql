CREATE TABLE Products_Product_Combinations
(
  Id                         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Product_Id                 INTEGER NOT NULL
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Product_Attribute_Value_Id INTEGER NOT NULL
    REFERENCES Products_Product_Attribute_Values
      ON DELETE CASCADE,
  Created_At                 datetime,
  Updated_At                 datetime
);
