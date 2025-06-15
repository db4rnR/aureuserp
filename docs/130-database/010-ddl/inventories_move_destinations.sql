CREATE TABLE Inventories_Move_Destinations
(
  Origin_Move_Id      INTEGER NOT NULL
    REFERENCES Inventories_Moves
      ON DELETE CASCADE,
  Destination_Move_Id INTEGER NOT NULL
    REFERENCES Inventories_Moves
      ON DELETE CASCADE
);
