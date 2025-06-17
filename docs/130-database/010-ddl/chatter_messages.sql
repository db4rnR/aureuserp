CREATE TABLE Chatter_Messages
(
  Id               INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Company_Id       INTEGER
    REFERENCES Companies
      ON DELETE CASCADE,
  Activity_Type_Id INTEGER
    REFERENCES Activity_Types
      ON DELETE CASCADE,
  Assigned_To      INTEGER
    REFERENCES Users
      ON DELETE CASCADE,
  Messageable_Type varchar                NOT NULL,
  Messageable_Id   INTEGER                NOT NULL,
  Type             varchar,
  Name             varchar,
  Subject          varchar,
  Body             TEXT,
  Summary          TEXT,
  Is_Internal      tinyint(1),
  Date_Deadline    date,
  Pinned_At        date,
  Log_Name         varchar,
  Causer_Type      varchar                NOT NULL,
  Causer_Id        INTEGER                NOT NULL,
  Event            varchar,
  Properties       TEXT,
  Created_At       datetime,
  Updated_At       datetime,
  Is_Read          tinyint(1) DEFAULT '0' NOT NULL
);

CREATE INDEX Chatter_Messages_Causer_Type_Causer_Id_Index
  ON Chatter_Messages (Causer_Type, Causer_Id);

CREATE INDEX Chatter_Messages_Messageable_Type_Messageable_Id_Index
  ON Chatter_Messages (Messageable_Type, Messageable_Id);
