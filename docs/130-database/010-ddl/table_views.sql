CREATE TABLE Table_Views
(
  Id              INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name            varchar                NOT NULL,
  Icon            varchar,
  Color           varchar,
  Is_Public       tinyint(1) DEFAULT '0' NOT NULL,
  Filters         TEXT,
  Filterable_Type varchar                NOT NULL,
  User_Id         INTEGER                NOT NULL
    REFERENCES Users
      ON DELETE CASCADE,
  Created_At      datetime,
  Updated_At      datetime
);
