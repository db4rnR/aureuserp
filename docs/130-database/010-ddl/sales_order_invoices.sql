CREATE TABLE Sales_Order_Invoices
(
  Order_Id INTEGER NOT NULL
    REFERENCES Sales_Orders
      ON DELETE CASCADE,
  Move_Id  INTEGER NOT NULL
    REFERENCES Accounts_Account_Moves
      ON DELETE CASCADE
);
