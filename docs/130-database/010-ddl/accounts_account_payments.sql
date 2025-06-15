CREATE TABLE Accounts_Account_Payments
(
  Id                                  INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Journal_Id                          INTEGER
    REFERENCES Accounts_Journals
      ON DELETE RESTRICT,
  Company_Id                          INTEGER
    REFERENCES Companies
      ON DELETE RESTRICT,
  Partner_Bank_Id                     INTEGER
                                              REFERENCES Partners_Bank_Accounts
                                                ON DELETE SET NULL,
  Paired_Internal_Transfer_Payment_Id INTEGER
                                              REFERENCES Accounts_Account_Payments
                                                ON DELETE SET NULL,
  Payment_Method_Line_Id              INTEGER
                                              REFERENCES Accounts_Payment_Method_Lines
                                                ON DELETE SET NULL,
  Payment_Method_Id                   INTEGER
                                              REFERENCES Accounts_Payment_Methods
                                                ON DELETE SET NULL,
  Currency_Id                         INTEGER
                                              REFERENCES Currencies
                                                ON DELETE SET NULL,
  Partner_Id                          INTEGER
                                              REFERENCES Partners_Partners
                                                ON DELETE SET NULL,
  Outstanding_Account_Id              INTEGER
                                              REFERENCES Accounts_Accounts
                                                ON DELETE SET NULL,
  Destination_Account_Id              INTEGER
                                              REFERENCES Accounts_Accounts
                                                ON DELETE SET NULL,
  Created_By                          INTEGER
                                              REFERENCES Users
                                                ON DELETE SET NULL,
  Name                                varchar,
  State                               varchar NOT NULL,
  Payment_Type                        varchar,
  Partner_Type                        varchar,
  Memo                                varchar,
  Payment_Reference                   varchar,
  Date                                date,
  Amount                              numeric,
  Amount_Company_Currency_Signed      numeric,
  Is_Reconciled                       tinyint(1),
  Is_Matched                          tinyint(1),
  Is_Sent                             tinyint(1),
  Source_Payment_Id                   INTEGER
                                              REFERENCES Accounts_Account_Payments
                                                ON DELETE SET NULL,
  Created_At                          datetime,
  Updated_At                          datetime,
  Move_Id                             INTEGER
                                              REFERENCES Accounts_Account_Moves
                                                ON DELETE SET NULL,
  Payment_Token_Id                    INTEGER
                                              REFERENCES Payments_Payment_Tokens
                                                ON DELETE SET NULL,
  Payment_Transaction_Id              INTEGER
                                              REFERENCES Payments_Payment_Transactions
                                                ON DELETE SET NULL
);
