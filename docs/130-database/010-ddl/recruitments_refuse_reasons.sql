CREATE TABLE Recruitments_Refuse_Reasons
(
  Id         INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Creator_Id INTEGER
                                    REFERENCES Users
                                      ON DELETE SET NULL,
  Sort       INTEGER    DEFAULT '0' NOT NULL,
  Name       varchar                NOT NULL,
  Template   varchar,
  Is_Active  tinyint(1) DEFAULT '1' NOT NULL,
  Created_At datetime,
  Updated_At datetime
);
