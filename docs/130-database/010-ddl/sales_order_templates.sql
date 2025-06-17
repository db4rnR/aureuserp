CREATE TABLE Sales_Order_Templates
(
  Id                    INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                  INTEGER    DEFAULT '0',
  Company_Id            INTEGER
                                REFERENCES Companies
                                  ON DELETE SET NULL,
  Number_Of_Days        INTEGER,
  Creator_Id            INTEGER
                                REFERENCES Users
                                  ON DELETE SET NULL,
  Name                  varchar NOT NULL,
  Note                  TEXT,
  Journal_Id            INTEGER,
  Is_Active             tinyint(1) DEFAULT '0',
  Require_Signature     tinyint(1) DEFAULT '0',
  Require_Payment       tinyint(1) DEFAULT '0',
  Prepayment_Percentage numeric    DEFAULT '0',
  Created_At            datetime,
  Updated_At            datetime
);
