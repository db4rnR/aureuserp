CREATE TABLE Currencies
(
  Id             INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name           varchar                NOT NULL,
  Symbol         varchar,
  Iso_Numeric    INTEGER,
  Decimal_Places INTEGER,
  Full_Name      varchar,
  Rounding       numeric    DEFAULT '0' NOT NULL,
  Active         tinyint(1) DEFAULT '1' NOT NULL,
  Created_At     datetime,
  Updated_At     datetime
);
