CREATE TABLE Recruitments_Stages
(
  Id             INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort           INTEGER,
  Creator_Id     INTEGER
                                        REFERENCES Users
                                          ON DELETE SET NULL,
  Name           varchar                NOT NULL,
  Legend_Blocked varchar                NOT NULL,
  Legend_Done    varchar                NOT NULL,
  Legend_Normal  varchar                NOT NULL,
  Requirements   TEXT,
  Hired_Stage    varchar,
  Fold           tinyint(1) DEFAULT '0' NOT NULL,
  Created_At     datetime,
  Updated_At     datetime,
  Is_Default     tinyint(1) DEFAULT '0' NOT NULL
);
