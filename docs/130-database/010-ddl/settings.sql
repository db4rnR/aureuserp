CREATE TABLE Settings
(
  Id         INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  "group"    varchar                NOT NULL,
  Name       varchar                NOT NULL,
  Locked     tinyint(1) DEFAULT '0' NOT NULL,
  Payload    TEXT                   NOT NULL,
  Created_At datetime,
  Updated_At datetime
);

CREATE UNIQUE INDEX Settings_Group_Name_Unique
  ON Settings ("group", Name);
