CREATE TABLE Table_View_Favorites
(
  Id              INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Is_Favorite     tinyint(1) DEFAULT '1' NOT NULL,
  View_Type       varchar                NOT NULL,
  View_Key        varchar                NOT NULL,
  Filterable_Type varchar                NOT NULL,
  User_Id         INTEGER                NOT NULL
    REFERENCES Users
      ON DELETE CASCADE,
  Created_At      datetime,
  Updated_At      datetime
);

CREATE UNIQUE INDEX Tbl_View_Fav_Unique
  ON Table_View_Favorites (View_Type, View_Key, Filterable_Type, User_Id);
