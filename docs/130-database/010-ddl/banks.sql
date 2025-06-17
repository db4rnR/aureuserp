CREATE TABLE Banks
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name       varchar,
  Code       varchar,
  Email      varchar,
  Phone      varchar,
  Street1    varchar,
  Street2    varchar,
  City       varchar,
  Zip        varchar,
  State_Id   INTEGER
    REFERENCES States
      ON DELETE RESTRICT,
  Country_Id INTEGER
    REFERENCES Countries
      ON DELETE RESTRICT,
  Creator_Id INTEGER NOT NULL
    REFERENCES Users,
  Deleted_At datetime,
  Created_At datetime,
  Updated_At datetime
);
