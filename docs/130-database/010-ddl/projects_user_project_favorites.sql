CREATE TABLE Projects_User_Project_Favorites
(
  Project_Id INTEGER NOT NULL
    REFERENCES Projects_Projects
      ON DELETE CASCADE,
  User_Id    INTEGER NOT NULL
    REFERENCES Users
      ON DELETE CASCADE
);
