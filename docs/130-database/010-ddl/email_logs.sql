CREATE TABLE Email_Logs
(
  Id              INTEGER  NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Recipient_Email varchar  NOT NULL,
  Recipient_Name  varchar  NOT NULL,
  Subject         varchar  NOT NULL,
  Status          varchar  NOT NULL,
  Error_Message   TEXT,
  Sent_At         datetime NOT NULL,
  Created_At      datetime,
  Updated_At      datetime
);
