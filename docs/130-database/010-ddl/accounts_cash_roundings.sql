CREATE TABLE Accounts_Cash_Roundings
(
  Id                INTEGER             NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Creator_Id        INTEGER
                                        REFERENCES Users
                                          ON DELETE SET NULL,
  Strategy          varchar             NOT NULL,
  Rounding_Method   varchar             NOT NULL,
  Name              varchar             NOT NULL,
  Rounding          numeric DEFAULT '0' NOT NULL,
  Profit_Account_Id INTEGER
                                        REFERENCES Accounts_Accounts
                                          ON DELETE SET NULL,
  Loss_Account_Id   INTEGER
                                        REFERENCES Accounts_Accounts
                                          ON DELETE SET NULL,
  Created_At        datetime,
  Updated_At        datetime
);
