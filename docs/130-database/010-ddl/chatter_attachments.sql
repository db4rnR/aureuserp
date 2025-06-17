CREATE TABLE Chatter_Attachments
(
  Id                 INTEGER NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Company_Id         INTEGER
    REFERENCES Companies
      ON DELETE CASCADE,
  Creator_Id         INTEGER
    REFERENCES Users
      ON DELETE CASCADE,
  Message_Id         INTEGER
    REFERENCES Chatter_Messages
      ON DELETE CASCADE,
  File_Size          varchar,
  Name               varchar,
  Messageable_Type   varchar NOT NULL,
  Messageable_Id     INTEGER NOT NULL,
  File_Path          varchar,
  Original_File_Name varchar,
  Mime_Type          varchar,
  Created_At         datetime,
  Updated_At         datetime
);

CREATE INDEX Chatter_Attachments_Messageable_Type_Messageable_Id_Index
  ON Chatter_Attachments (Messageable_Type, Messageable_Id);
