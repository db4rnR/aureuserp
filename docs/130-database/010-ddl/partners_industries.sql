CREATE TABLE Partners_Industries
(
  Id          INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name        varchar                NOT NULL,
  Description TEXT,
  Is_Active   tinyint(1) DEFAULT '1' NOT NULL,
  Creator_Id  INTEGER
                                     REFERENCES Users
                                       ON DELETE SET NULL,
  Deleted_At  datetime,
  Created_At  datetime,
  Updated_At  datetime
);
