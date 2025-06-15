CREATE TABLE Sales_Order_Lines
(
  Id                        INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                      INTEGER,
  Order_Id                  INTEGER                NOT NULL
    REFERENCES Sales_Orders
      ON DELETE CASCADE,
  Company_Id                INTEGER
                                                   REFERENCES Companies
                                                     ON DELETE SET NULL,
  Currency_Id               INTEGER
                                                   REFERENCES Currencies
                                                     ON DELETE SET NULL,
  Order_Partner_Id          INTEGER
                                                   REFERENCES Partners_Partners
                                                     ON DELETE SET NULL,
  Salesman_Id               INTEGER
                                                   REFERENCES Users
                                                     ON DELETE SET NULL,
  Product_Id                INTEGER
                                                   REFERENCES Products_Products
                                                     ON DELETE SET NULL,
  Product_Uom_Id            INTEGER
                                                   REFERENCES Unit_Of_Measures
                                                     ON DELETE SET NULL,
  Linked_Sale_Order_Sale_Id INTEGER
                                                   REFERENCES Sales_Order_Lines
                                                     ON DELETE SET NULL,
  Product_Packaging_Id      INTEGER
                                                   REFERENCES Products_Packagings
                                                     ON DELETE SET NULL,
  Creator_Id                INTEGER
                                                   REFERENCES Users
                                                     ON DELETE SET NULL,
  State                     varchar,
  Display_Type              varchar,
  Virtual_Id                varchar,
  Linked_Virtual_Id         varchar,
  Qty_Delivered_Method      varchar,
  Invoice_Status            varchar,
  Analytic_Distribution     varchar,
  Name                      varchar                NOT NULL,
  Product_Uom_Qty           numeric    DEFAULT '0' NOT NULL,
  Product_Qty               numeric    DEFAULT '0' NOT NULL,
  Price_Unit                numeric    DEFAULT '0' NOT NULL,
  Discount                  numeric    DEFAULT '0',
  Price_Subtotal            numeric    DEFAULT '0',
  Price_Total               numeric    DEFAULT '0',
  Price_Reduce_Taxexcl      numeric    DEFAULT '0',
  Price_Reduce_Taxinc       numeric    DEFAULT '0',
  Purchase_Price            numeric    DEFAULT '0',
  Margin                    numeric    DEFAULT '0',
  Margin_Percent            numeric    DEFAULT '0',
  Qty_Delivered             numeric    DEFAULT '0',
  Qty_Invoiced              numeric    DEFAULT '0',
  Qty_To_Invoice            numeric    DEFAULT '0',
  Untaxed_Amount_Invoiced   numeric    DEFAULT '0',
  Untaxed_Amount_To_Invoice numeric    DEFAULT '0',
  Is_Downpayment            tinyint(1) DEFAULT '0',
  Is_Expense                tinyint(1) DEFAULT '0',
  Create_Date               datetime,
  Write_Date                datetime,
  Technical_Price_Unit      numeric    DEFAULT '0',
  Price_Tax                 numeric    DEFAULT '0',
  Product_Packaging_Qty     numeric    DEFAULT '0',
  Customer_Lead             numeric    DEFAULT '0' NOT NULL,
  Created_At                datetime,
  Updated_At                datetime,
  Route_Id                  INTEGER
    REFERENCES Inventories_Routes
      ON DELETE RESTRICT,
  Warehouse_Id              INTEGER
                                                   REFERENCES Inventories_Warehouses
                                                     ON DELETE SET NULL
);
