CREATE TABLE Purchases_Orders
(
  Id                       INTEGER                 NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                     varchar                 NOT NULL,
  Description              TEXT,
  Priority                 varchar    DEFAULT '0'  NOT NULL,
  Origin                   varchar,
  Partner_Reference        varchar,
  State                    varchar,
  Invoice_Status           varchar    DEFAULT 'no' NOT NULL,
  Receipt_Status           varchar    DEFAULT 'no' NOT NULL,
  Untaxed_Amount           numeric    DEFAULT '0'  NOT NULL,
  Tax_Amount               numeric    DEFAULT '0'  NOT NULL,
  Total_Amount             numeric    DEFAULT '0'  NOT NULL,
  Total_Cc_Amount          numeric    DEFAULT '0'  NOT NULL,
  Currency_Rate            numeric    DEFAULT '0'  NOT NULL,
  Invoice_Count            INTEGER    DEFAULT '0'  NOT NULL,
  Ordered_At               datetime                NOT NULL,
  Approved_At              datetime,
  Planned_At               datetime,
  Calendar_Start_At        datetime,
  Effective_Date           datetime,
  Incoterm_Location        varchar,
  Mail_Reminder_Confirmed  tinyint(1) DEFAULT '0',
  Mail_Reception_Confirmed tinyint(1) DEFAULT '0',
  Mail_Reception_Declined  tinyint(1) DEFAULT '0',
  Report_Grids             tinyint(1) DEFAULT '0',
  Requisition_Id           INTEGER
                                                   REFERENCES Purchases_Requisitions
                                                     ON DELETE SET NULL,
  Purchases_Group_Id       INTEGER
                                                   REFERENCES Purchases_Order_Groups
                                                     ON DELETE SET NULL,
  Partner_Id               INTEGER                 NOT NULL
    REFERENCES Partners_Partners
      ON DELETE RESTRICT,
  Currency_Id              INTEGER                 NOT NULL
    REFERENCES Currencies
      ON DELETE RESTRICT,
  Fiscal_Position_Id       INTEGER
                                                   REFERENCES Accounts_Fiscal_Positions
                                                     ON DELETE SET NULL,
  Payment_Term_Id          INTEGER
                                                   REFERENCES Accounts_Payment_Terms
                                                     ON DELETE SET NULL,
  Incoterm_Id              INTEGER
                                                   REFERENCES Accounts_Incoterms
                                                     ON DELETE SET NULL,
  User_Id                  INTEGER
                                                   REFERENCES Users
                                                     ON DELETE SET NULL,
  Company_Id               INTEGER                 NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Creator_Id               INTEGER
                                                   REFERENCES Users
                                                     ON DELETE SET NULL,
  Created_At               datetime,
  Updated_At               datetime,
  Operation_Type_Id        INTEGER
    REFERENCES Inventories_Operation_Types
      ON DELETE RESTRICT
);

CREATE INDEX Purchases_Orders_Approved_At_Index
  ON Purchases_Orders (Approved_At);

CREATE INDEX Purchases_Orders_Company_Id_Index
  ON Purchases_Orders (Company_Id);

CREATE INDEX Purchases_Orders_Name_Index
  ON Purchases_Orders (Name);

CREATE INDEX Purchases_Orders_Ordered_At_Index
  ON Purchases_Orders (Ordered_At);

CREATE INDEX Purchases_Orders_Planned_At_Index
  ON Purchases_Orders (Planned_At);

CREATE INDEX Purchases_Orders_Priority_Index
  ON Purchases_Orders (Priority);

CREATE INDEX Purchases_Orders_State_Index
  ON Purchases_Orders (State);

CREATE INDEX Purchases_Orders_User_Id_Index
  ON Purchases_Orders (User_Id);
