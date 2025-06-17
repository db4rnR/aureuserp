CREATE TABLE Accounts_Account_Move_Lines
(
  Id                       INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                     INTEGER,
  Move_Id                  INTEGER                NOT NULL
    REFERENCES Accounts_Account_Moves
      ON DELETE CASCADE,
  Journal_Id               INTEGER
                                                  REFERENCES Accounts_Journals
                                                    ON DELETE SET NULL,
  Company_Id               INTEGER
                                                  REFERENCES Companies
                                                    ON DELETE SET NULL,
  Company_Currency_Id      INTEGER
                                                  REFERENCES Currencies
                                                    ON DELETE SET NULL,
  Reconcile_Id             INTEGER
                                                  REFERENCES Accounts_Reconciles
                                                    ON DELETE SET NULL,
  Payment_Id               INTEGER
                                                  REFERENCES Accounts_Account_Payments
                                                    ON DELETE SET NULL,
  Tax_Repartition_Line_Id  INTEGER
    REFERENCES Accounts_Tax_Partition_Lines
      ON DELETE RESTRICT,
  Account_Id               INTEGER
    REFERENCES Accounts_Accounts
      ON DELETE CASCADE,
  Currency_Id              INTEGER                NOT NULL
    REFERENCES Currencies
      ON DELETE RESTRICT,
  Partner_Id               INTEGER
    REFERENCES Partners_Partners
      ON DELETE RESTRICT,
  Group_Tax_Id             INTEGER
                                                  REFERENCES Accounts_Taxes
                                                    ON DELETE SET NULL,
  Tax_Line_Id              INTEGER
    REFERENCES Accounts_Taxes
      ON DELETE RESTRICT,
  Tax_Group_Id             INTEGER
                                                  REFERENCES Accounts_Tax_Groups
                                                    ON DELETE SET NULL,
  Statement_Id             INTEGER
                                                  REFERENCES Accounts_Bank_Statements
                                                    ON DELETE SET NULL,
  Statement_Line_Id        INTEGER
                                                  REFERENCES Accounts_Bank_Statement_Lines
                                                    ON DELETE SET NULL,
  Product_Id               INTEGER
    REFERENCES Products_Products
      ON DELETE RESTRICT,
  Uom_Id                   INTEGER
    REFERENCES Unit_Of_Measures
      ON DELETE RESTRICT,
  Created_By               INTEGER
                                                  REFERENCES Users
                                                    ON DELETE SET NULL,
  Move_Name                varchar,
  Parent_State             varchar,
  Reference                varchar,
  Name                     varchar,
  Matching_Number          varchar,
  Display_Type             varchar    DEFAULT 'product',
  Date                     date,
  Invoice_Date             date,
  Date_Maturity            date,
  Discount_Date            date,
  Analytic_Distribution    TEXT,
  Debit                    numeric,
  Credit                   numeric,
  Balance                  numeric,
  Amount_Currency          numeric,
  Tax_Base_Amount          numeric,
  Amount_Residual          numeric,
  Amount_Residual_Currency numeric,
  Quantity                 numeric,
  Price_Unit               numeric,
  Price_Subtotal           numeric,
  Price_Total              numeric,
  Discount                 numeric,
  Discount_Amount_Currency numeric,
  Discount_Balance         numeric,
  Is_Imported              tinyint(1) DEFAULT '0' NOT NULL,
  Tax_Tag_Invert           tinyint(1) DEFAULT '0' NOT NULL,
  Reconciled               tinyint(1) DEFAULT '0' NOT NULL,
  Is_Downpayment           tinyint(1) DEFAULT '0' NOT NULL,
  Created_At               datetime,
  Updated_At               datetime,
  Full_Reconcile_Id        INTEGER
                                                  REFERENCES Accounts_Full_Reconciles
                                                    ON DELETE SET NULL,
  Purchase_Order_Line_Id   INTEGER
                                                  REFERENCES Purchases_Order_Lines
                                                    ON DELETE SET NULL
);
