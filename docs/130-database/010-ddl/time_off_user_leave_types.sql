CREATE TABLE Time_Off_User_Leave_Types
(
  User_Id       INTEGER NOT NULL
    REFERENCES Users
      ON DELETE CASCADE,
  Leave_Type_Id INTEGER NOT NULL
    REFERENCES Time_Off_Leave_Types
      ON DELETE CASCADE
);
