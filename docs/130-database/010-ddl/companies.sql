CREATE TABLE Companies
(
  Id                  INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Parent_Id           INTEGER
    REFERENCES Companies
      ON DELETE CASCADE,
  Currency_Id         INTEGER
                                             REFERENCES Currencies
                                               ON DELETE SET NULL,
  Creator_Id          INTEGER
                                             REFERENCES Users
                                               ON DELETE SET NULL,
  Sort                INTEGER,
  Name                varchar                NOT NULL,
  Company_Id          varchar,
  Tax_Id              varchar,
  Registration_Number varchar,
  Email               varchar,
  Phone               varchar,
  Mobile              varchar,
  Website             varchar,
  Color               varchar,
  Is_Active           tinyint(1) DEFAULT '1' NOT NULL,
  Founded_Date        date,
  Deleted_At          datetime,
  Created_At          datetime,
  Updated_At          datetime,
  Partner_Id          INTEGER                NOT NULL
    REFERENCES Partners_Partners
      ON DELETE RESTRICT,
  Street1             varchar,
  Street2             varchar,
  City                varchar,
  Zip                 varchar,
  State_Id            INTEGER
    REFERENCES States
      ON DELETE RESTRICT,
  Country_Id          INTEGER
    REFERENCES Countries
      ON DELETE RESTRICT
);

CREATE UNIQUE INDEX Companies_Company_Id_Unique
  ON Companies (Company_Id);

CREATE UNIQUE INDEX Companies_Tax_Id_Unique
  ON Companies (Tax_Id);
