CREATE TABLE Job_Batches
(
  Id             varchar NOT NULL
    PRIMARY KEY,
  Name           varchar NOT NULL,
  Total_Jobs     INTEGER NOT NULL,
  Pending_Jobs   INTEGER NOT NULL,
  Failed_Jobs    INTEGER NOT NULL,
  Failed_Job_Ids TEXT    NOT NULL,
  Options        TEXT,
  Cancelled_At   INTEGER,
  Created_At     INTEGER NOT NULL,
  Finished_At    INTEGER
);
