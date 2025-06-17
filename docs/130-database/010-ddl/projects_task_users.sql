CREATE TABLE Projects_Task_Users
(
  Id         INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Task_Id    INTEGER NOT NULL
    REFERENCES Projects_Tasks
      ON DELETE CASCADE,
  User_Id    INTEGER NOT NULL
    REFERENCES Users
      ON DELETE CASCADE,
  Stage_Id   INTEGER
                     REFERENCES Projects_Task_Stages
                       ON DELETE SET NULL,
  Created_At datetime,
  Updated_At datetime
);

CREATE UNIQUE INDEX Projects_Task_Users_Task_Id_User_Id_Unique
  ON Projects_Task_Users (Task_Id, User_Id);
