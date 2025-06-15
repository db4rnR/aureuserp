CREATE TABLE Accounts_Bank_Statements
(
  Id               INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Company_Id       INTEGER
                                          REFERENCES Companies
                                            ON DELETE SET NULL,
  Journal_Id       INTEGER
                                          REFERENCES Accounts_Journals
                                            ON DELETE SET NULL,
  Created_By       INTEGER
                                          REFERENCES Users
                                            ON DELETE SET NULL,
  Name             varchar,
  Reference        varchar,
  First_Line_Index varchar,
  Date             date,
  Balance_Start    numeric    DEFAULT '0' NOT NULL,
  Balance_End      numeric    DEFAULT '0' NOT NULL,
  Balance_End_Real numeric    DEFAULT '0' NOT NULL,
  Is_Completed     tinyint(1) DEFAULT '0' NOT NULL,
  Created_At       datetime,
  Updated_At       datetime
);
