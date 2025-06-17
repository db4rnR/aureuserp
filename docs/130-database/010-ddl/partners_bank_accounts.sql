CREATE TABLE Partners_Bank_Accounts
(
  Id                  INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Account_Number      varchar                NOT NULL,
  Account_Holder_Name varchar                NOT NULL,
  Is_Active           tinyint(1) DEFAULT '1' NOT NULL,
  Can_Send_Money      tinyint(1) DEFAULT '0' NOT NULL,
  Creator_Id          INTEGER
                                             REFERENCES Users
                                               ON DELETE SET NULL,
  Partner_Id          INTEGER                NOT NULL
    REFERENCES Partners_Partners
      ON DELETE CASCADE,
  Bank_Id             INTEGER                NOT NULL
    REFERENCES Banks
      ON DELETE CASCADE,
  Deleted_At          datetime,
  Created_At          datetime,
  Updated_At          datetime
);

CREATE UNIQUE INDEX Partners_Bank_Accounts_Account_Number_Unique
  ON Partners_Bank_Accounts (Account_Number);
