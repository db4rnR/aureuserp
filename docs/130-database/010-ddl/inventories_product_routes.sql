CREATE TABLE Inventories_Product_Routes
(
  Product_Id INTEGER NOT NULL
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Route_Id   INTEGER NOT NULL
    REFERENCES Inventories_Routes
      ON DELETE CASCADE
);
