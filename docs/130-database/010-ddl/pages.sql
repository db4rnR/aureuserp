CREATE TABLE Pages
(
  Id         INTEGER                   NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Title      varchar                   NOT NULL,
  Slug       varchar                   NOT NULL,
  Layout     varchar DEFAULT 'default' NOT NULL,
  Blocks     TEXT                      NOT NULL,
  Parent_Id  INTEGER
    REFERENCES Pages
      ON UPDATE CASCADE ON DELETE CASCADE,
  Created_At datetime,
  Updated_At datetime
);

CREATE INDEX Pages_Layout_Index
  ON Pages (Layout);

CREATE UNIQUE INDEX Pages_Slug_Parent_Id_Unique
  ON Pages (Slug, Parent_Id);

CREATE INDEX Pages_Title_Index
  ON Pages (Title);
