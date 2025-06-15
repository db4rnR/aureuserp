CREATE TABLE Activity_Types
(
  Id                     INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                   INTEGER,
  Delay_Count            INTEGER,
  Delay_Unit             varchar                NOT NULL,
  Delay_From             varchar                NOT NULL,
  Icon                   varchar,
  Decoration_Type        varchar,
  Chaining_Type          varchar    DEFAULT 'suggest',
  Plugin                 varchar,
  Category               varchar,
  Name                   varchar                NOT NULL,
  Summary                TEXT,
  Default_Note           TEXT,
  Is_Active              tinyint(1) DEFAULT '1' NOT NULL,
  Keep_Done              tinyint(1) DEFAULT '0' NOT NULL,
  Creator_Id             INTEGER
                                                REFERENCES Users
                                                  ON DELETE SET NULL,
  Default_User_Id        INTEGER
                                                REFERENCES Users
                                                  ON DELETE SET NULL,
  Activity_Plan_Id       INTEGER
    REFERENCES Activity_Plans
      ON DELETE CASCADE,
  Triggered_Next_Type_Id INTEGER
    REFERENCES Activity_Types
      ON DELETE RESTRICT,
  Deleted_At             datetime,
  Created_At             datetime,
  Updated_At             datetime
);
