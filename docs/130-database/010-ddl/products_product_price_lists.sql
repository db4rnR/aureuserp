CREATE TABLE Products_Product_Price_Lists
(
  Id          INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort        INTEGER,
  Currency_Id INTEGER                NOT NULL
    REFERENCES Currencies
      ON DELETE RESTRICT,
  Company_Id  INTEGER
                                     REFERENCES Companies
                                       ON DELETE SET NULL,
  Creator_Id  INTEGER
                                     REFERENCES Users
                                       ON DELETE SET NULL,
  Name        varchar                NOT NULL,
  Is_Active   tinyint(1) DEFAULT '1' NOT NULL,
  Created_At  datetime,
  Updated_At  datetime
);
