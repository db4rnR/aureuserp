CREATE TABLE Accounts_Payment_Terms
(
  Id                  INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Company_Id          INTEGER
                              REFERENCES Companies
                                ON DELETE SET NULL,
  Sort                INTEGER,
  Discount_Days       INTEGER,
  Creator_Id          INTEGER
                              REFERENCES Users
                                ON DELETE SET NULL,
  Early_Pay_Discount  varchar,
  Name                varchar NOT NULL,
  Note                varchar,
  Display_On_Invoice  tinyint(1) DEFAULT '0',
  Early_Discount      tinyint(1) DEFAULT '0',
  Discount_Percentage numeric    DEFAULT '0',
  Deleted_At          datetime,
  Created_At          datetime,
  Updated_At          datetime
);
