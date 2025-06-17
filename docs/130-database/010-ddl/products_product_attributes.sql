CREATE TABLE Products_Product_Attributes
(
  Id           INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort         INTEGER,
  Product_Id   INTEGER NOT NULL
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Attribute_Id INTEGER NOT NULL
    REFERENCES Products_Attributes
      ON DELETE CASCADE,
  Creator_Id   INTEGER
                       REFERENCES Users
                         ON DELETE SET NULL,
  Created_At   datetime,
  Updated_At   datetime
);
