CREATE TABLE Products_Products
(
  Id                          INTEGER                   NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Type                        varchar                   NOT NULL,
  Name                        varchar                   NOT NULL,
  Service_Tracking            varchar    DEFAULT 'none' NOT NULL,
  Reference                   varchar,
  Barcode                     varchar,
  Price                       numeric,
  Cost                        numeric,
  Volume                      numeric,
  Weight                      numeric,
  Description                 TEXT,
  Description_Purchase        TEXT,
  Description_Sale            TEXT,
  Enable_Sales                tinyint(1),
  Enable_Purchase             tinyint(1),
  Is_Favorite                 tinyint(1),
  Is_Configurable             tinyint(1),
  Sort                        INTEGER,
  Images                      TEXT,
  Parent_Id                   INTEGER
    REFERENCES Products_Products
      ON DELETE CASCADE,
  Uom_Id                      INTEGER                   NOT NULL
    REFERENCES Unit_Of_Measures
      ON DELETE RESTRICT,
  Uom_Po_Id                   INTEGER                   NOT NULL
    REFERENCES Unit_Of_Measures
      ON DELETE RESTRICT,
  Category_Id                 INTEGER                   NOT NULL
    REFERENCES Products_Categories
      ON DELETE CASCADE,
  Company_Id                  INTEGER
                                                        REFERENCES Companies
                                                          ON DELETE SET NULL,
  Creator_Id                  INTEGER
                                                        REFERENCES Users
                                                          ON DELETE SET NULL,
  Deleted_At                  datetime,
  Created_At                  datetime,
  Updated_At                  datetime,
  Sale_Delay                  INTEGER,
  Tracking                    varchar    DEFAULT 'qty',
  Description_Picking         TEXT,
  Description_Pickingout      TEXT,
  Description_Pickingin       TEXT,
  Is_Storable                 tinyint(1) DEFAULT '0',
  Expiration_Time             INTEGER    DEFAULT '0',
  Use_Time                    INTEGER    DEFAULT '0',
  Removal_Time                INTEGER    DEFAULT '0',
  Alert_Time                  INTEGER    DEFAULT '0',
  Use_Expiration_Date         tinyint(1) DEFAULT '0',
  Responsible_Id              INTEGER
                                                        REFERENCES Users
                                                          ON DELETE SET NULL,
  Property_Account_Income_Id  INTEGER
                                                        REFERENCES Accounts_Accounts
                                                          ON DELETE SET NULL,
  Property_Account_Expense_Id INTEGER
                                                        REFERENCES Accounts_Accounts
                                                          ON DELETE SET NULL,
  Image                       varchar,
  Service_Type                varchar,
  Sale_Line_Warn              varchar,
  Expense_Policy              TEXT,
  Invoice_Policy              TEXT,
  Sales_Ok                    tinyint(1) DEFAULT '1'    NOT NULL,
  Purchase_Ok                 tinyint(1) DEFAULT '1'    NOT NULL,
  Sale_Line_Warn_Msg          varchar
);
