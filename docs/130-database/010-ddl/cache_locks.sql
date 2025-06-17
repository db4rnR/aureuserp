CREATE TABLE Cache_Locks
(
  Key        varchar NOT NULL
    PRIMARY KEY,
  Owner      varchar NOT NULL,
  Expiration INTEGER NOT NULL
);
