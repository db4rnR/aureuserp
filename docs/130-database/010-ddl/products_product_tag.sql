CREATE TABLE Products_Product_Tag
(
  Tag_Id     INTEGER NOT NULL
    REFERENCES Products_Tags
      ON DELETE CASCADE,
  Product_Id INTEGER NOT NULL
    REFERENCES Products_Products
      ON DELETE CASCADE
);
