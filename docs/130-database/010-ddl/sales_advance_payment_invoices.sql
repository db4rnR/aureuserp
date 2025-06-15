CREATE TABLE Sales_Advance_Payment_Invoices
(
  Id                     INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Currency_Id            INTEGER
                                                REFERENCES Currencies
                                                  ON DELETE SET NULL,
  Company_Id             INTEGER
                                                REFERENCES Companies
                                                  ON DELETE SET NULL,
  Creator_Id             INTEGER
                                                REFERENCES Users
                                                  ON DELETE SET NULL,
  Advance_Payment_Method varchar                NOT NULL,
  Fixed_Amount           numeric    DEFAULT '0',
  Amount                 numeric    DEFAULT '0',
  Deduct_Down_Payments   tinyint(1) DEFAULT '0' NOT NULL,
  Consolidated_Billing   tinyint(1) DEFAULT '0' NOT NULL,
  Created_At             datetime,
  Updated_At             datetime
);
