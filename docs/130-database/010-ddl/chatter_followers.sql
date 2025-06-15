CREATE TABLE Chatter_Followers
(
  Id              INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Followable_Type varchar NOT NULL,
  Followable_Id   INTEGER NOT NULL,
  Partner_Id      INTEGER
    REFERENCES Partners_Partners
      ON DELETE CASCADE,
  Followed_At     datetime,
  Created_At      datetime,
  Updated_At      datetime
);

CREATE INDEX Chatter_Followers_Followable_Type_Followable_Id_Index
  ON Chatter_Followers (Followable_Type, Followable_Id);

CREATE UNIQUE INDEX Chatter_Followers_Unique
  ON Chatter_Followers (Followable_Type, Followable_Id, Partner_Id);
