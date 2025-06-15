CREATE TABLE Accounts_Payment_Registers
(
  Id                          INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Currency_Id                 INTEGER
                                      REFERENCES Currencies
                                        ON DELETE SET NULL,
  Journal_Id                  INTEGER
                                      REFERENCES Accounts_Journals
                                        ON DELETE SET NULL,
  Partner_Bank_Id             INTEGER
                                      REFERENCES Partners_Bank_Accounts
                                        ON DELETE SET NULL,
  Custom_User_Currency_Id     INTEGER
                                      REFERENCES Currencies
                                        ON DELETE SET NULL,
  Source_Currency_Id          INTEGER
                                      REFERENCES Currencies
                                        ON DELETE SET NULL,
  Company_Id                  INTEGER
                                      REFERENCES Companies
                                        ON DELETE SET NULL,
  Partner_Id                  INTEGER
                                      REFERENCES Partners_Partners
                                        ON DELETE SET NULL,
  Payment_Method_Line_Id      INTEGER
                                      REFERENCES Accounts_Payment_Method_Lines
                                        ON DELETE SET NULL,
  Writeoff_Account_Id         INTEGER
                                      REFERENCES Accounts_Accounts
                                        ON DELETE SET NULL,
  Creator_Id                  INTEGER
                                      REFERENCES Users
                                        ON DELETE SET NULL,
  Communication               varchar,
  Installments_Mode           varchar,
  Payment_Type                varchar,
  Partner_Type                varchar,
  Payment_Difference_Handling varchar,
  Writeoff_Label              varchar,
  Payment_Date                date,
  Amount                      numeric,
  Custom_User_Amount          numeric,
  Source_Amount               numeric,
  Source_Amount_Currency      numeric,
  Group_Payment               tinyint(1) DEFAULT '0',
  Can_Group_Payments          tinyint(1) DEFAULT '0',
  Payment_Token_Id            INTEGER,
  Created_At                  datetime,
  Updated_At                  datetime
);
