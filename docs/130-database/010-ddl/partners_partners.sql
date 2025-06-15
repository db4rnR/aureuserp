CREATE TABLE Partners_Partners
(
  Id                                       INTEGER                         NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Account_Type                             varchar    DEFAULT 'individual' NOT NULL,
  Sub_Type                                 varchar    DEFAULT 'partner',
  Name                                     varchar                         NOT NULL,
  Avatar                                   varchar,
  Email                                    varchar,
  Job_Title                                varchar,
  Website                                  varchar,
  Tax_Id                                   varchar,
  Phone                                    varchar,
  Mobile                                   varchar,
  Color                                    varchar,
  Company_Registry                         varchar,
  Reference                                varchar,
  Parent_Id                                INTEGER
                                                                           REFERENCES Partners_Partners
                                                                             ON DELETE SET NULL,
  Creator_Id                               INTEGER
                                                                           REFERENCES Users
                                                                             ON DELETE SET NULL,
  User_Id                                  INTEGER
                                                                           REFERENCES Users
                                                                             ON DELETE SET NULL,
  Title_Id                                 INTEGER
                                                                           REFERENCES Partners_Titles
                                                                             ON DELETE SET NULL,
  Company_Id                               INTEGER
                                                                           REFERENCES Companies
                                                                             ON DELETE SET NULL,
  Industry_Id                              INTEGER
                                                                           REFERENCES Partners_Industries
                                                                             ON DELETE SET NULL,
  Deleted_At                               datetime,
  Created_At                               datetime,
  Updated_At                               datetime,
  Street1                                  varchar,
  Street2                                  varchar,
  City                                     varchar,
  Zip                                      varchar,
  State_Id                                 INTEGER
    REFERENCES States
      ON DELETE RESTRICT,
  Country_Id                               INTEGER
    REFERENCES Countries
      ON DELETE RESTRICT,
  Message_Bounce                           INTEGER,
  Supplier_Rank                            INTEGER,
  Customer_Rank                            INTEGER,
  Invoice_Warning                          varchar,
  Autopost_Bills                           varchar,
  Credit_Limit                             varchar,
  Ignore_Abnormal_Invoice_Date             varchar,
  Ignore_Abnormal_Invoice_Amount           varchar,
  Invoice_Sending_Method                   varchar,
  Invoice_Edi_Format_Store                 varchar,
  Trust                                    INTEGER,
  Invoice_Warn_Msg                         INTEGER,
  Debit_Limit                              numeric,
  Peppol_Endpoint                          varchar,
  Peppol_Eas                               varchar,
  Sale_Warn                                varchar,
  Sale_Warn_Msg                            varchar,
  Comment                                  TEXT,
  Property_Account_Payable_Id              INTEGER
                                                                           REFERENCES Accounts_Accounts
                                                                             ON DELETE SET NULL,
  Property_Account_Receivable_Id           INTEGER
                                                                           REFERENCES Accounts_Accounts
                                                                             ON DELETE SET NULL,
  Property_Account_Position_Id             INTEGER
                                                                           REFERENCES Accounts_Accounts
                                                                             ON DELETE SET NULL,
  Property_Payment_Term_Id                 INTEGER
                                                                           REFERENCES Accounts_Payment_Terms
                                                                             ON DELETE SET NULL,
  Property_Supplier_Payment_Term_Id        INTEGER
                                                                           REFERENCES Accounts_Payment_Terms
                                                                             ON DELETE SET NULL,
  Property_Inbound_Payment_Method_Line_Id  INTEGER
                                                                           REFERENCES Accounts_Payment_Method_Lines
                                                                             ON DELETE SET NULL,
  Property_Outbound_Payment_Method_Line_Id INTEGER
                                                                           REFERENCES Accounts_Payment_Method_Lines
                                                                             ON DELETE SET NULL,
  Email_Verified_At                        datetime,
  Is_Active                                tinyint(1) DEFAULT '1'          NOT NULL,
  Password                                 varchar,
  Remember_Token                           varchar
);

CREATE INDEX Partners_Partners_Company_Registry_Index
  ON Partners_Partners (Company_Registry);

CREATE INDEX Partners_Partners_Mobile_Index
  ON Partners_Partners (Mobile);

CREATE INDEX Partners_Partners_Name_Index
  ON Partners_Partners (Name);

CREATE INDEX Partners_Partners_Phone_Index
  ON Partners_Partners (Phone);

CREATE INDEX Partners_Partners_Reference_Index
  ON Partners_Partners (Reference);

CREATE INDEX Partners_Partners_Sub_Type_Index
  ON Partners_Partners (Sub_Type);

CREATE INDEX Partners_Partners_Tax_Id_Index
  ON Partners_Partners (Tax_Id);
