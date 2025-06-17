CREATE TABLE Purchases_Order_Lines
(
  Id                           INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                         TEXT                   NOT NULL,
  State                        varchar,
  Sort                         INTEGER,
  Qty_Received_Method          varchar,
  Display_Type                 varchar,
  Product_Qty                  numeric                NOT NULL,
  Product_Uom_Qty              double,
  Product_Packaging_Qty        double,
  Price_Tax                    double,
  Discount                     numeric    DEFAULT '0' NOT NULL,
  Price_Unit                   numeric    DEFAULT '0' NOT NULL,
  Price_Subtotal               numeric    DEFAULT '0' NOT NULL,
  Price_Total                  numeric    DEFAULT '0' NOT NULL,
  Qty_Invoiced                 numeric    DEFAULT '0' NOT NULL,
  Qty_Received                 numeric    DEFAULT '0' NOT NULL,
  Qty_Received_Manual          numeric    DEFAULT '0' NOT NULL,
  Qty_To_Invoice               numeric    DEFAULT '0' NOT NULL,
  Is_Downpayment               tinyint(1) DEFAULT '0' NOT NULL,
  Planned_At                   datetime,
  Product_Description_Variants varchar,
  Propagate_Cancel             tinyint(1),
  Price_Total_Cc               numeric    DEFAULT '0' NOT NULL,
  Uom_Id                       INTEGER
                                                      REFERENCES Unit_Of_Measures
                                                        ON DELETE SET NULL,
  Product_Id                   INTEGER
                                                      REFERENCES Products_Products
                                                        ON DELETE SET NULL,
  Product_Packaging_Id         INTEGER
                                                      REFERENCES Products_Packagings
                                                        ON DELETE SET NULL,
  Order_Id                     INTEGER                NOT NULL
    REFERENCES Purchases_Orders
      ON DELETE CASCADE,
  Partner_Id                   INTEGER
                                                      REFERENCES Partners_Partners
                                                        ON DELETE SET NULL,
  Currency_Id                  INTEGER
                                                      REFERENCES Currencies
                                                        ON DELETE SET NULL,
  Company_Id                   INTEGER
                                                      REFERENCES Companies
                                                        ON DELETE SET NULL,
  Creator_Id                   INTEGER
                                                      REFERENCES Users
                                                        ON DELETE SET NULL,
  Created_At                   datetime,
  Updated_At                   datetime,
  Final_Location_Id            INTEGER
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Order_Point_Id               INTEGER
    REFERENCES Inventories_Order_Points
      ON DELETE RESTRICT
);

CREATE INDEX Purchases_Order_Lines_Order_Id_Index
  ON Purchases_Order_Lines (Order_Id);

CREATE INDEX Purchases_Order_Lines_Partner_Id_Index
  ON Purchases_Order_Lines (Partner_Id);

CREATE INDEX Purchases_Order_Lines_Planned_At_Index
  ON Purchases_Order_Lines (Planned_At);

CREATE INDEX Purchases_Order_Lines_Product_Id_Index
  ON Purchases_Order_Lines (Product_Id);
