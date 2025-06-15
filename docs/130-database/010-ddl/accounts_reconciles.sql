CREATE TABLE Accounts_Reconciles
(
  Id                                INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                              INTEGER,
  Company_Id                        INTEGER                NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Past_Months_Limit                 INTEGER,
  Created_By                        INTEGER
                                                           REFERENCES Users
                                                             ON DELETE SET NULL,
  Rule_Type                         varchar                NOT NULL,
  Matching_Order                    varchar                NOT NULL,
  Counter_Part_Type                 varchar,
  Match_Nature                      varchar,
  Match_Amount                      varchar,
  Match_Label                       varchar,
  Match_Level_Parameters            varchar,
  Match_Note                        varchar,
  Match_Note_Parameters             varchar,
  Match_Transaction_Type            varchar,
  Match_Transaction_Type_Parameters varchar,
  Payment_Tolerance_Type            varchar,
  Decimal_Separator                 varchar,
  Name                              varchar                NOT NULL,
  Auto_Reconcile                    tinyint(1)             NOT NULL,
  To_Check                          tinyint(1) DEFAULT '0' NOT NULL,
  Match_Text_Location_Label         tinyint(1) DEFAULT '0' NOT NULL,
  Match_Text_Location_Note          tinyint(1) DEFAULT '0' NOT NULL,
  Match_Text_Location_Reference     tinyint(1) DEFAULT '0' NOT NULL,
  Match_Same_Currency               tinyint(1) DEFAULT '0' NOT NULL,
  Allow_Payment_Tolerance           tinyint(1) DEFAULT '0' NOT NULL,
  Match_Partner                     tinyint(1) DEFAULT '0' NOT NULL,
  Match_Amount_Min                  numeric,
  Match_Amount_Max                  numeric,
  Payment_Tolerance_Parameters      numeric,
  Created_At                        datetime,
  Updated_At                        datetime
);
