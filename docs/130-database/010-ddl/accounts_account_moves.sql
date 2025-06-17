CREATE TABLE Accounts_Account_Moves
(
  Id                                INTEGER                    NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                              INTEGER,
  Journal_Id                        INTEGER
    REFERENCES Accounts_Journals
      ON DELETE RESTRICT,
  Company_Id                        INTEGER
                                                               REFERENCES Companies
                                                                 ON DELETE SET NULL,
  Tax_Cash_Basis_Origin_Move_Id     INTEGER
                                                               REFERENCES Accounts_Account_Moves
                                                                 ON DELETE SET NULL,
  Auto_Post_Origin_Id               INTEGER
                                                               REFERENCES Accounts_Account_Moves
                                                                 ON DELETE SET NULL,
  Origin_Payment_Id                 INTEGER
                                                               REFERENCES Accounts_Account_Payments
                                                                 ON DELETE SET NULL,
  Secure_Sequence_Number            INTEGER,
  Invoice_Payment_Term_Id           INTEGER
                                                               REFERENCES Accounts_Payment_Terms
                                                                 ON DELETE SET NULL,
  Partner_Id                        INTEGER
                                                               REFERENCES Partners_Partners
                                                                 ON DELETE SET NULL,
  Commercial_Partner_Id             INTEGER
                                                               REFERENCES Partners_Partners
                                                                 ON DELETE SET NULL,
  Partner_Shipping_Id               INTEGER
                                                               REFERENCES Partners_Partners
                                                                 ON DELETE SET NULL,
  Partner_Bank_Id                   INTEGER
                                                               REFERENCES Partners_Bank_Accounts
                                                                 ON DELETE SET NULL,
  Fiscal_Position_Id                INTEGER
                                                               REFERENCES Accounts_Fiscal_Positions
                                                                 ON DELETE SET NULL,
  Currency_Id                       INTEGER
    REFERENCES Currencies
      ON DELETE RESTRICT,
  Reversed_Entry_Id                 INTEGER
                                                               REFERENCES Accounts_Account_Moves
                                                                 ON DELETE SET NULL,
  Campaign_Id                       INTEGER
                                                               REFERENCES Utm_Campaigns
                                                                 ON DELETE SET NULL,
  Invoice_User_Id                   INTEGER
                                                               REFERENCES Users
                                                                 ON DELETE SET NULL,
  Statement_Line_Id                 INTEGER
                                                               REFERENCES Accounts_Bank_Statement_Lines
                                                                 ON DELETE SET NULL,
  Invoice_Incoterm_Id               INTEGER
                                                               REFERENCES Accounts_Incoterms
                                                                 ON DELETE SET NULL,
  Preferred_Payment_Method_Line_Id  INTEGER
                                                               REFERENCES Accounts_Payment_Method_Lines
                                                                 ON DELETE SET NULL,
  Invoice_Cash_Rounding_Id          INTEGER
                                                               REFERENCES Accounts_Cash_Roundings
                                                                 ON DELETE SET NULL,
  Creator_Id                        INTEGER
                                                               REFERENCES Users
                                                                 ON DELETE SET NULL,
  Sequence_Prefix                   varchar,
  Access_Token                      varchar,
  Name                              varchar,
  Reference                         varchar,
  State                             varchar    DEFAULT 'draft' NOT NULL,
  Move_Type                         varchar                    NOT NULL,
  Auto_Post                         tinyint(1) DEFAULT '0'     NOT NULL,
  Inalterable_Hash                  varchar,
  Payment_Reference                 varchar,
  Qr_Code_Method                    varchar,
  Payment_State                     varchar    DEFAULT 'not_paid',
  Invoice_Source_Email              varchar,
  Invoice_Partner_Display_Name      varchar,
  Invoice_Origin                    varchar,
  Incoterm_Location                 varchar,
  Date                              date                       NOT NULL,
  Auto_Post_Until                   date,
  Invoice_Date                      date,
  Invoice_Date_Due                  date,
  Delivery_Date                     date,
  Sending_Data                      TEXT,
  Narration                         TEXT,
  Invoice_Currency_Rate             numeric,
  Amount_Untaxed                    numeric,
  Amount_Tax                        numeric,
  Amount_Total                      numeric,
  Amount_Residual                   numeric,
  Amount_Untaxed_Signed             numeric,
  Amount_Untaxed_In_Currency_Signed numeric,
  Amount_Tax_Signed                 numeric,
  Amount_Total_Signed               numeric,
  Amount_Total_In_Currency_Signed   numeric,
  Amount_Residual_Signed            numeric,
  Quick_Edit_Total_Amount           numeric,
  Is_Storno                         tinyint(1) DEFAULT '0'     NOT NULL,
  Always_Tax_Exigible               tinyint(1) DEFAULT '0'     NOT NULL,
  Checked                           tinyint(1) DEFAULT '0'     NOT NULL,
  Posted_Before                     tinyint(1) DEFAULT '0'     NOT NULL,
  Made_Sequence_Gap                 tinyint(1) DEFAULT '0'     NOT NULL,
  Is_Manually_Modified              tinyint(1) DEFAULT '0'     NOT NULL,
  Is_Move_Sent                      tinyint(1) DEFAULT '0'     NOT NULL,
  Source_Id                         INTEGER
                                                               REFERENCES Utm_Sources
                                                                 ON DELETE SET NULL,
  Medium_Id                         INTEGER
                                                               REFERENCES Utm_Mediums
                                                                 ON DELETE SET NULL,
  Created_At                        datetime,
  Updated_At                        datetime,
  Tax_Cash_Basis_Reconcile_Id       INTEGER
                                                               REFERENCES Accounts_Partial_Reconciles
                                                                 ON DELETE SET NULL
);
