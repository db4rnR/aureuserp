CREATE TABLE Failed_Jobs
(
  Id         INTEGER                            NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Uuid       varchar                            NOT NULL,
  Connection TEXT                               NOT NULL,
  Queue      TEXT                               NOT NULL,
  Payload    TEXT                               NOT NULL,
  Exception  TEXT                               NOT NULL,
  Failed_At  datetime DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE UNIQUE INDEX Failed_Jobs_Uuid_Unique
  ON Failed_Jobs (Uuid);
