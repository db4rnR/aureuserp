CREATE TABLE Activity_Type_Suggestions
(
  Activity_Type_Id           INTEGER NOT NULL
    REFERENCES Activity_Types
      ON DELETE CASCADE,
  Suggested_Activity_Type_Id INTEGER NOT NULL
    REFERENCES Activity_Types
      ON DELETE CASCADE
);
