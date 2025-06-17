CREATE TABLE Purchases_Order_Operations
(
  Purchase_Order_Id      INTEGER NOT NULL
    REFERENCES Purchases_Orders
      ON DELETE CASCADE,
  Inventory_Operation_Id INTEGER NOT NULL
    REFERENCES Inventories_Operations
      ON DELETE CASCADE
);
