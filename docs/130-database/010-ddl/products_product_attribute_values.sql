CREATE TABLE Products_Product_Attribute_Values
(
  Id                   INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Extra_Price          numeric,
  Product_Id           INTEGER
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Attribute_Id         INTEGER
    REFERENCES Products_Attributes
      ON DELETE CASCADE,
  Product_Attribute_Id INTEGER NOT NULL
    REFERENCES Products_Product_Attributes
      ON DELETE CASCADE,
  Attribute_Option_Id  INTEGER NOT NULL
    REFERENCES Products_Attribute_Options
      ON DELETE CASCADE
);
