CREATE TABLE Payments_Payment_Tokens
(
  Id                    INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Company_Id            INTEGER
                                               REFERENCES Companies
                                                 ON DELETE SET NULL,
  Payment_Method_Id     INTEGER
    REFERENCES Payments_Payment_Methods
      ON DELETE RESTRICT,
  Partner_Id            INTEGER
    REFERENCES Partners_Partners
      ON DELETE RESTRICT,
  Created_By            INTEGER
                                               REFERENCES Users
                                                 ON DELETE SET NULL,
  Payment_Details       TEXT,
  Provider_Reference_Id varchar,
  Is_Active             tinyint(1) DEFAULT '0' NOT NULL,
  Created_At            datetime,
  Updated_At            datetime
);
