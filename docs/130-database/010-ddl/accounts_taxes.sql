CREATE TABLE Accounts_Taxes
(
  Id                               INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                             INTEGER,
  Company_Id                       INTEGER NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Tax_Group_Id                     INTEGER NOT NULL
    REFERENCES Accounts_Tax_Groups
      ON DELETE RESTRICT,
  Cash_Basis_Transition_Account_Id INTEGER
                                           REFERENCES Accounts_Accounts
                                             ON DELETE SET NULL,
  Country_Id                       INTEGER
                                           REFERENCES Countries
                                             ON DELETE SET NULL,
  Creator_Id                       INTEGER
                                           REFERENCES Users
                                             ON DELETE SET NULL,
  Type_Tax_Use                     varchar NOT NULL,
  Tax_Scope                        varchar,
  Formula                          varchar,
  Amount_Type                      varchar NOT NULL,
  Price_Include_Override           varchar,
  Tax_Exigibility                  varchar,
  Name                             varchar,
  Description                      varchar,
  Invoice_Label                    varchar,
  Invoice_Legal_Notes              TEXT,
  Amount                           numeric    DEFAULT '0',
  Is_Active                        tinyint(1) DEFAULT '0',
  Include_Base_Amount              tinyint(1) DEFAULT '0',
  Is_Base_Affected                 tinyint(1) DEFAULT '0',
  Analytic                         tinyint(1) DEFAULT '0',
  Created_At                       datetime,
  Updated_At                       datetime
);
