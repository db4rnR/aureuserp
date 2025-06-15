CREATE TABLE Migrations
(
  Id        INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Migration varchar NOT NULL,
  Batch     INTEGER NOT NULL
);
