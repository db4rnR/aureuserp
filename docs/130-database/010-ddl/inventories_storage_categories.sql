CREATE TABLE Inventories_Storage_Categories
(
  Id                 INTEGER             NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name               varchar             NOT NULL,
  Sort               INTEGER,
  Allow_New_Products varchar             NOT NULL,
  Max_Weight         numeric DEFAULT '0' NOT NULL,
  Company_Id         INTEGER
                                         REFERENCES Companies
                                           ON DELETE SET NULL,
  Creator_Id         INTEGER
                                         REFERENCES Users
                                           ON DELETE SET NULL,
  Created_At         datetime,
  Updated_At         datetime
);
