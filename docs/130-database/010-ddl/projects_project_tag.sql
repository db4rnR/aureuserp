CREATE TABLE Projects_Project_Tag
(
  Tag_Id     INTEGER NOT NULL
    REFERENCES Projects_Tags
      ON DELETE CASCADE,
  Project_Id INTEGER NOT NULL
    REFERENCES Projects_Projects
      ON DELETE CASCADE
);
