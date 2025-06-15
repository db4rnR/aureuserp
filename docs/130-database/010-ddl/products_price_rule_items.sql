CREATE TABLE Products_Price_Rule_Items
(
  Id                 INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Apply_To           varchar NOT NULL,
  Display_Apply_To   varchar NOT NULL,
  Base               varchar NOT NULL,
  Type               varchar NOT NULL,
  Min_Quantity       numeric DEFAULT '0',
  Fixed_Price        numeric DEFAULT '0',
  Price_Discount     numeric DEFAULT '0',
  Price_Round        numeric DEFAULT '0',
  Price_Surcharge    numeric DEFAULT '0',
  Price_Markup       numeric DEFAULT '0',
  Price_Min_Margin   numeric DEFAULT '0',
  Percent_Price      numeric DEFAULT '0',
  Starts_At          datetime,
  Ends_At            datetime,
  Price_Rule_Id      INTEGER NOT NULL
    REFERENCES Products_Price_Rules
      ON DELETE CASCADE,
  Base_Price_Rule_Id INTEGER
                             REFERENCES Products_Price_Rules
                               ON DELETE SET NULL,
  Product_Id         INTEGER NOT NULL
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Category_Id        INTEGER NOT NULL
    REFERENCES Products_Categories
      ON DELETE CASCADE,
  Currency_Id        INTEGER
                             REFERENCES Currencies
                               ON DELETE SET NULL,
  Company_Id         INTEGER
                             REFERENCES Companies
                               ON DELETE SET NULL,
  Creator_Id         INTEGER
                             REFERENCES Users
                               ON DELETE SET NULL,
  Created_At         datetime,
  Updated_At         datetime
);
