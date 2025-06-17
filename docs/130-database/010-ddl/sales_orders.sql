CREATE TABLE Sales_Orders
(
  Id                     INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Utm_Source_Id          INTEGER
                                                REFERENCES Utm_Sources
                                                  ON DELETE SET NULL,
  Campaign_Id            INTEGER
                                                REFERENCES Utm_Campaigns
                                                  ON DELETE SET NULL,
  Medium_Id              INTEGER
                                                REFERENCES Utm_Mediums
                                                  ON DELETE SET NULL,
  Company_Id             INTEGER                NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Partner_Id             INTEGER                NOT NULL
    REFERENCES Partners_Partners
      ON DELETE RESTRICT,
  Journal_Id             INTEGER
                                                REFERENCES Accounts_Journals
                                                  ON DELETE SET NULL,
  Partner_Invoice_Id     INTEGER                NOT NULL
    REFERENCES Partners_Partners
      ON DELETE RESTRICT,
  Partner_Shipping_Id    INTEGER                NOT NULL
    REFERENCES Partners_Partners
      ON DELETE RESTRICT,
  Fiscal_Position_Id     INTEGER
                                                REFERENCES Accounts_Fiscal_Positions
                                                  ON DELETE SET NULL,
  Payment_Term_Id        INTEGER
                                                REFERENCES Accounts_Payment_Terms
                                                  ON DELETE SET NULL,
  Currency_Id            INTEGER                NOT NULL
    REFERENCES Currencies
      ON DELETE RESTRICT,
  User_Id                INTEGER
                                                REFERENCES Users
                                                  ON DELETE SET NULL,
  Team_Id                INTEGER
                                                REFERENCES Sales_Teams
                                                  ON DELETE SET NULL,
  Creator_Id             INTEGER
                                                REFERENCES Users
                                                  ON DELETE SET NULL,
  Sale_Order_Template_Id INTEGER
                                                REFERENCES Sales_Order_Templates
                                                  ON DELETE SET NULL,
  Access_Token           varchar,
  Name                   varchar                NOT NULL,
  State                  varchar,
  Client_Order_Ref       varchar,
  Origin                 varchar,
  Reference              varchar,
  Signed_By              varchar,
  Invoice_Status         varchar,
  Validity_Date          date,
  Note                   TEXT,
  Currency_Rate          numeric    DEFAULT '0',
  Amount_Untaxed         numeric    DEFAULT '0',
  Amount_Tax             numeric    DEFAULT '0',
  Amount_Total           numeric    DEFAULT '0',
  Locked                 tinyint(1) DEFAULT '0' NOT NULL,
  Require_Signature      tinyint(1) DEFAULT '0' NOT NULL,
  Require_Payment        tinyint(1) DEFAULT '0' NOT NULL,
  Commitment_Date        date,
  Date_Order             date                   NOT NULL,
  Signed_On              date,
  Prepayment_Percent     numeric    DEFAULT '0',
  Deleted_At             datetime,
  Created_At             datetime,
  Updated_At             datetime,
  Delivery_Status        varchar    DEFAULT 'no',
  Warehouse_Id           INTEGER
                                                REFERENCES Inventories_Warehouses
                                                  ON DELETE SET NULL
);
