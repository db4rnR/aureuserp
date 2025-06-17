CREATE TABLE Purchases_Order_Account_Moves
(
  Order_Id INTEGER NOT NULL
    REFERENCES Purchases_Orders
      ON DELETE CASCADE,
  Move_Id  INTEGER NOT NULL
    REFERENCES Accounts_Account_Moves
      ON DELETE CASCADE
);
