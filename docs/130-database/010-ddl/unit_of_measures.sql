CREATE TABLE Unit_Of_Measures
(
  Id          INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Type        varchar NOT NULL,
  Name        varchar NOT NULL,
  Factor      numeric DEFAULT '0',
  Rounding    numeric DEFAULT '0',
  Category_Id INTEGER NOT NULL
    REFERENCES Unit_Of_Measure_Categories
      ON DELETE RESTRICT,
  Creator_Id  INTEGER
                      REFERENCES Users
                        ON DELETE SET NULL,
  Deleted_At  datetime,
  Created_At  datetime,
  Updated_At  datetime
);
