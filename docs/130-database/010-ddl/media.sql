CREATE TABLE Media
(
  Id          INTEGER                  NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Disk        varchar DEFAULT 'public' NOT NULL,
  Directory   varchar DEFAULT 'media'  NOT NULL,
  Visibility  varchar DEFAULT 'public' NOT NULL,
  Name        varchar                  NOT NULL,
  Path        varchar                  NOT NULL,
  Width       INTEGER,
  Height      INTEGER,
  Size        INTEGER,
  Type        varchar DEFAULT 'image'  NOT NULL,
  Ext         varchar                  NOT NULL,
  Alt         varchar,
  Title       varchar,
  Description TEXT,
  Caption     TEXT,
  Exif        TEXT,
  Curations   TEXT,
  Created_At  datetime,
  Updated_At  datetime,
  Tenant_Id   INTEGER
);
