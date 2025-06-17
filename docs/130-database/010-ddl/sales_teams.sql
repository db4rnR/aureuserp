CREATE TABLE Sales_Teams
(
  Id              INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort            INTEGER    DEFAULT '0',
  Company_Id      INTEGER
                          REFERENCES Companies
                            ON DELETE SET NULL,
  User_Id         INTEGER
                          REFERENCES Users
                            ON DELETE SET NULL,
  Color           varchar,
  Creator_Id      INTEGER
                          REFERENCES Users
                            ON DELETE SET NULL,
  Name            varchar NOT NULL,
  Is_Active       tinyint(1) DEFAULT '0',
  Invoiced_Target numeric    DEFAULT '0',
  Deleted_At      datetime,
  Created_At      datetime,
  Updated_At      datetime
);
