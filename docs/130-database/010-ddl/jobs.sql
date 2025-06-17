CREATE TABLE Jobs
(
  Id           INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Queue        varchar NOT NULL,
  Payload      TEXT    NOT NULL,
  Attempts     INTEGER NOT NULL,
  Reserved_At  INTEGER,
  Available_At INTEGER NOT NULL,
  Created_At   INTEGER NOT NULL
);

CREATE INDEX Jobs_Queue_Index
  ON Jobs (Queue);
