CREATE TABLE Products_Attribute_Options
(
  Id           INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name         varchar NOT NULL,
  Color        varchar,
  Extra_Price  numeric,
  Sort         INTEGER,
  Attribute_Id INTEGER NOT NULL
    REFERENCES Products_Attributes
      ON DELETE CASCADE,
  Creator_Id   INTEGER
                       REFERENCES Users
                         ON DELETE SET NULL,
  Created_At   datetime,
  Updated_At   datetime
);
