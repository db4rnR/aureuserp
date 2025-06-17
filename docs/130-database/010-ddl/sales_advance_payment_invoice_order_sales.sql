CREATE TABLE Sales_Advance_Payment_Invoice_Order_Sales
(
  Advance_Payment_Invoice_Id INTEGER NOT NULL
    REFERENCES Sales_Advance_Payment_Invoices
      ON DELETE CASCADE,
  Order_Id                   INTEGER NOT NULL
    REFERENCES Sales_Orders
      ON DELETE CASCADE
);
