CREATE TABLE Website_Pages
(
  Id                INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Title             varchar                NOT NULL,
  Content           TEXT                   NOT NULL,
  Slug              varchar                NOT NULL,
  Is_Published      tinyint(1) DEFAULT '0' NOT NULL,
  Is_Header_Visible tinyint(1) DEFAULT '0' NOT NULL,
  Is_Footer_Visible tinyint(1) DEFAULT '0' NOT NULL,
  Published_At      datetime,
  Meta_Title        varchar,
  Meta_Keywords     varchar,
  Meta_Description  TEXT,
  Creator_Id        INTEGER
                                           REFERENCES Users
                                             ON DELETE SET NULL,
  Deleted_At        datetime,
  Created_At        datetime,
  Updated_At        datetime
);

CREATE UNIQUE INDEX Website_Pages_Slug_Unique
  ON Website_Pages (Slug);
