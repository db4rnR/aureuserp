CREATE TABLE Cache
(
  Key        varchar NOT NULL
    PRIMARY KEY,
  Value      TEXT    NOT NULL,
  Expiration INTEGER NOT NULL
);
