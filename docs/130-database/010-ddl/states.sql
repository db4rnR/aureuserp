CREATE TABLE States
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Country_Id INTEGER NOT NULL
    REFERENCES Countries
      ON DELETE CASCADE,
  Name       varchar NOT NULL,
  Code       varchar NOT NULL,
  Created_At datetime,
  Updated_At datetime
);
