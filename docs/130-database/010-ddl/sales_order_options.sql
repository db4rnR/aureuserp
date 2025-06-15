CREATE TABLE Sales_Order_Options
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort       INTEGER DEFAULT '0',
  Order_Id   INTEGER
    REFERENCES Sales_Orders
      ON DELETE CASCADE,
  Product_Id INTEGER NOT NULL
    REFERENCES Products_Products
      ON DELETE RESTRICT,
  Line_Id    INTEGER
                     REFERENCES Sales_Order_Lines
                       ON DELETE SET NULL,
  Uom_Id     INTEGER
                     REFERENCES Unit_Of_Measures
                       ON DELETE SET NULL,
  Creator_Id INTEGER
                     REFERENCES Users
                       ON DELETE SET NULL,
  Name       varchar NOT NULL,
  Quantity   numeric,
  Price_Unit numeric,
  Discount   numeric,
  Created_At datetime,
  Updated_At datetime
);
