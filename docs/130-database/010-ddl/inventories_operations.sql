CREATE TABLE Inventories_Operations
(
  Id                      INTEGER                     NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Name                    varchar,
  Description             TEXT,
  Origin                  varchar,
  Move_Type               varchar    DEFAULT 'direct' NOT NULL,
  State                   varchar,
  Is_Favorite             tinyint(1) DEFAULT '0'      NOT NULL,
  Has_Deadline_Issue      tinyint(1) DEFAULT '0'      NOT NULL,
  Is_Printed              tinyint(1) DEFAULT '0'      NOT NULL,
  Is_Locked               tinyint(1) DEFAULT '0'      NOT NULL,
  Deadline                datetime,
  Scheduled_At            datetime,
  Closed_At               datetime,
  User_Id                 INTEGER
                                                      REFERENCES Users
                                                        ON DELETE SET NULL,
  Owner_Id                INTEGER
                                                      REFERENCES Users
                                                        ON DELETE SET NULL,
  Operation_Type_Id       INTEGER                     NOT NULL
    REFERENCES Inventories_Operation_Types
      ON DELETE RESTRICT,
  Source_Location_Id      INTEGER                     NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Destination_Location_Id INTEGER                     NOT NULL
    REFERENCES Inventories_Locations
      ON DELETE RESTRICT,
  Back_Order_Id           INTEGER
                                                      REFERENCES Inventories_Operations
                                                        ON DELETE SET NULL,
  Return_Id               INTEGER
                                                      REFERENCES Inventories_Operations
                                                        ON DELETE SET NULL,
  Partner_Id              INTEGER
                                                      REFERENCES Partners_Partners
                                                        ON DELETE SET NULL,
  Company_Id              INTEGER
                                                      REFERENCES Companies
                                                        ON DELETE SET NULL,
  Creator_Id              INTEGER
                                                      REFERENCES Users
                                                        ON DELETE SET NULL,
  Created_At              datetime,
  Updated_At              datetime,
  Sale_Order_Id           INTEGER
    REFERENCES Sales_Orders
      ON DELETE RESTRICT
);
