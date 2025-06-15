CREATE TABLE Countries
(
  Id             INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Currency_Id    INTEGER
                                        REFERENCES Currencies
                                          ON DELETE SET NULL,
  Phone_Code     varchar,
  Code           varchar,
  Name           varchar,
  State_Required tinyint(1) DEFAULT '0' NOT NULL,
  Zip_Required   tinyint(1) DEFAULT '0' NOT NULL,
  Created_At     datetime,
  Updated_At     datetime
);
