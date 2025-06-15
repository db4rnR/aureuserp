CREATE TABLE Accounts_Product_Taxes
(
  Product_Id INTEGER NOT NULL
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Tax_Id     INTEGER NOT NULL
    REFERENCES Accounts_Taxes
      ON DELETE CASCADE
);
