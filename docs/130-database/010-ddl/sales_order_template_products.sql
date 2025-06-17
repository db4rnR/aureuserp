CREATE TABLE Sales_Order_Template_Products
(
  Id                INTEGER             NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort              INTEGER,
  Order_Template_Id INTEGER             NOT NULL
    REFERENCES Sales_Order_Templates
      ON DELETE CASCADE,
  Company_Id        INTEGER
                                        REFERENCES Companies
                                          ON DELETE SET NULL,
  Product_Id        INTEGER
                                        REFERENCES Products_Products
                                          ON DELETE SET NULL,
  Product_Uom_Id    INTEGER
                                        REFERENCES Unit_Of_Measures
                                          ON DELETE SET NULL,
  Creator_Id        INTEGER
                                        REFERENCES Users
                                          ON DELETE SET NULL,
  Display_Type      varchar,
  Name              varchar,
  Quantity          numeric DEFAULT '0' NOT NULL,
  Created_At        datetime,
  Updated_At        datetime
);
