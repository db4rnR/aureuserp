CREATE TABLE Inventories_Scrap_Tags
(
  Tag_Id   INTEGER NOT NULL
    REFERENCES Inventories_Tags
      ON DELETE CASCADE,
  Scrap_Id INTEGER NOT NULL
    REFERENCES Inventories_Scraps
      ON DELETE CASCADE
);
