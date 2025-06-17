CREATE TABLE Accounts_Fiscal_Positions
(
  Id               INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort             INTEGER,
  Company_Id       INTEGER                NOT NULL
    REFERENCES Companies
      ON DELETE RESTRICT,
  Country_Id       INTEGER
                                          REFERENCES Countries
                                            ON DELETE SET NULL,
  Country_Group_Id INTEGER
                                          REFERENCES Countries
                                            ON DELETE SET NULL,
  Creator_Id       INTEGER
                                          REFERENCES Users
                                            ON DELETE SET NULL,
  Zip_From         varchar,
  Zip_To           varchar,
  Foreign_Vat      varchar,
  Name             varchar                NOT NULL,
  Notes            TEXT,
  Auto_Reply       tinyint(1) DEFAULT '0' NOT NULL,
  Vat_Required     tinyint(1) DEFAULT '0' NOT NULL,
  Created_At       datetime,
  Updated_At       datetime
);
