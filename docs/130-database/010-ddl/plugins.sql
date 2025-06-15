CREATE TABLE Plugins
(
  Id             INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name           varchar                NOT NULL,
  Author         varchar,
  Summary        TEXT,
  Description    TEXT,
  Latest_Version varchar,
  License        varchar,
  Is_Active      tinyint(1) DEFAULT '0' NOT NULL,
  Is_Installed   tinyint(1) DEFAULT '0' NOT NULL,
  Sort           INTEGER,
  Created_At     datetime,
  Updated_At     datetime
);

CREATE UNIQUE INDEX Plugins_Name_Unique
  ON Plugins (Name);
