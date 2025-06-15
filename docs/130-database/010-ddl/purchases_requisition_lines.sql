CREATE TABLE Purchases_Requisition_Lines
(
  Id             INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Qty            numeric DEFAULT '0',
  Price_Unit     numeric DEFAULT '0',
  Requisition_Id INTEGER NOT NULL
    REFERENCES Purchases_Requisitions
      ON DELETE CASCADE,
  Product_Id     INTEGER NOT NULL
    REFERENCES Products_Products
      ON DELETE RESTRICT,
  Uom_Id         INTEGER
                         REFERENCES Unit_Of_Measures
                           ON DELETE SET NULL,
  Company_Id     INTEGER
                         REFERENCES Companies
                           ON DELETE SET NULL,
  Creator_Id     INTEGER
                         REFERENCES Users
                           ON DELETE SET NULL,
  Created_At     datetime,
  Updated_At     datetime
);
