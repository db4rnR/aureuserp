CREATE TABLE Sales_Order_Line_Taxes
(
  Order_Line_Id INTEGER NOT NULL
    REFERENCES Sales_Order_Lines
      ON DELETE CASCADE,
  Tax_Id        INTEGER NOT NULL
    REFERENCES Accounts_Taxes
      ON DELETE CASCADE
);
