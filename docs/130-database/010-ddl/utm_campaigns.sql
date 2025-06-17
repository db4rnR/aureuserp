CREATE TABLE Utm_Campaigns
(
  Id               INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  User_Id          INTEGER
    REFERENCES Users
      ON DELETE RESTRICT,
  Stage_Id         INTEGER                NOT NULL
    REFERENCES Utm_Stages
      ON DELETE RESTRICT,
  Color            varchar,
  Created_By       INTEGER
                                          REFERENCES Users
                                            ON DELETE SET NULL,
  Name             varchar                NOT NULL,
  Title            varchar                NOT NULL,
  Is_Active        tinyint(1) DEFAULT '0' NOT NULL,
  Is_Auto_Campaign tinyint(1) DEFAULT '0' NOT NULL,
  Company_Id       INTEGER
                                          REFERENCES Companies
                                            ON DELETE SET NULL,
  Created_At       datetime,
  Updated_At       datetime
);
