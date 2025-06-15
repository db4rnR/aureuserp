CREATE TABLE Accounts_Journals
(
  Id                       INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Default_Account_Id       INTEGER
    REFERENCES Accounts_Accounts
      ON DELETE RESTRICT,
  Suspense_Account_Id      INTEGER
    REFERENCES Accounts_Accounts
      ON DELETE RESTRICT,
  Sort                     INTEGER,
  Currency_Id              INTEGER
                                   REFERENCES Currencies
                                     ON DELETE SET NULL,
  Company_Id               INTEGER
    REFERENCES Companies
      ON DELETE RESTRICT,
  Profit_Account_Id        INTEGER
                                   REFERENCES Accounts_Accounts
                                     ON DELETE SET NULL,
  Loss_Account_Id          INTEGER
                                   REFERENCES Accounts_Accounts
                                     ON DELETE SET NULL,
  Bank_Account_Id          INTEGER
    REFERENCES Banks
      ON DELETE RESTRICT,
  Creator_Id               INTEGER
                                   REFERENCES Users
                                     ON DELETE SET NULL,
  Color                    varchar,
  Access_Token             varchar,
  Code                     varchar,
  Type                     varchar NOT NULL,
  Invoice_Reference_Type   varchar NOT NULL,
  Invoice_Reference_Model  varchar NOT NULL,
  Bank_Statements_Source   varchar,
  Name                     varchar NOT NULL,
  Order_Override_Regex     TEXT,
  Auto_Check_On_Post       tinyint(1) DEFAULT '0',
  Restrict_Mode_Hash_Table tinyint(1) DEFAULT '0',
  Refund_Order             tinyint(1) DEFAULT '0',
  Payment_Order            tinyint(1) DEFAULT '0',
  Show_On_Dashboard        tinyint(1) DEFAULT '0',
  Created_At               datetime,
  Updated_At               datetime
);
