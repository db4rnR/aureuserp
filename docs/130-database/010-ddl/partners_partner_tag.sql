CREATE TABLE Partners_Partner_Tag
(
  Tag_Id     INTEGER NOT NULL
    REFERENCES Partners_Tags
      ON DELETE CASCADE,
  Partner_Id INTEGER NOT NULL
    REFERENCES Partners_Partners
      ON DELETE CASCADE
);
