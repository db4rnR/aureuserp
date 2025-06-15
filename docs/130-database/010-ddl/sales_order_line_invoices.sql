CREATE TABLE Sales_Order_Line_Invoices
(
  Order_Line_Id   INTEGER NOT NULL
    REFERENCES Sales_Order_Lines
      ON DELETE CASCADE,
  Invoice_Line_Id INTEGER NOT NULL
    REFERENCES Accounts_Account_Move_Lines
      ON DELETE CASCADE
);
