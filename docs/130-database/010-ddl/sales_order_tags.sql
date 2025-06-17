CREATE TABLE Sales_Order_Tags
(
  Order_Id INTEGER
    REFERENCES Sales_Orders
      ON DELETE CASCADE,
  Tag_Id   INTEGER
    REFERENCES Sales_Tags
      ON DELETE CASCADE
);
