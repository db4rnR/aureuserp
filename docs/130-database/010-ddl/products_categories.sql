CREATE TABLE Products_Categories
(
  Id                            INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                          varchar NOT NULL,
  Full_Name                     varchar,
  Parent_Path                   varchar,
  Parent_Id                     INTEGER
    REFERENCES Products_Categories
      ON DELETE CASCADE,
  Creator_Id                    INTEGER
                                        REFERENCES Users
                                          ON DELETE SET NULL,
  Created_At                    datetime,
  Updated_At                    datetime,
  Product_Properties_Definition TEXT
);

CREATE INDEX Products_Categories_Name_Index
  ON Products_Categories (Name);
