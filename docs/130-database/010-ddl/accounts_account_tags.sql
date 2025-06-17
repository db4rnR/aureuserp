CREATE TABLE Accounts_Account_Tags
(
  Id            INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Color         varchar,
  Country_Id    INTEGER
                                       REFERENCES Countries
                                         ON DELETE SET NULL,
  Creator_Id    INTEGER
                                       REFERENCES Users
                                         ON DELETE SET NULL,
  Applicability varchar                NOT NULL,
  Name          varchar                NOT NULL,
  Tax_Negate    tinyint(1) DEFAULT '0' NOT NULL,
  Created_At    datetime,
  Updated_At    datetime
);
