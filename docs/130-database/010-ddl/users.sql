CREATE TABLE Users
(
  Id                  INTEGER                         NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                varchar                         NOT NULL,
  Email               varchar                         NOT NULL,
  Email_Verified_At   datetime,
  Language            varchar,
  Is_Active           tinyint(1) DEFAULT '1'          NOT NULL,
  Password            varchar                         NOT NULL,
  Remember_Token      varchar,
  Deleted_At          datetime,
  Created_At          datetime,
  Updated_At          datetime,
  Resource_Permission varchar    DEFAULT 'individual' NOT NULL,
  Default_Company_Id  INTEGER
    REFERENCES Companies
      ON DELETE RESTRICT,
  Partner_Id          INTEGER
    REFERENCES Partners_Partners
      ON DELETE RESTRICT
);

CREATE UNIQUE INDEX Users_Email_Unique
  ON Users (Email);
