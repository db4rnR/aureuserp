CREATE TABLE User_Invitations
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Email      varchar NOT NULL,
  Created_At datetime,
  Updated_At datetime
);
