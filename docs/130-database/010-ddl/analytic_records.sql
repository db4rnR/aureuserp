CREATE TABLE Analytic_Records
(
  Id          INTEGER             NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Type        varchar             NOT NULL,
  Name        varchar,
  Date        date                NOT NULL,
  Amount      numeric DEFAULT '0' NOT NULL,
  Unit_Amount numeric DEFAULT '0' NOT NULL,
  User_Id     INTEGER
                                  REFERENCES Users
                                    ON DELETE SET NULL,
  Partner_Id  INTEGER
                                  REFERENCES Partners_Partners
                                    ON DELETE SET NULL,
  Company_Id  INTEGER
                                  REFERENCES Companies
                                    ON DELETE SET NULL,
  Creator_Id  INTEGER
                                  REFERENCES Users
                                    ON DELETE SET NULL,
  Created_At  datetime,
  Updated_At  datetime,
  Project_Id  INTEGER
                                  REFERENCES Projects_Projects
                                    ON DELETE SET NULL,
  Task_Id     INTEGER
                                  REFERENCES Projects_Tasks
                                    ON DELETE SET NULL
);
