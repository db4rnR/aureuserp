CREATE TABLE Payments_Payment_Methods
(
  Id                        INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                      INTEGER,
  Primary_Payment_Method_Id INTEGER
                                                   REFERENCES Payments_Payment_Methods
                                                     ON DELETE SET NULL,
  Created_By                INTEGER
                                                   REFERENCES Users
                                                     ON DELETE SET NULL,
  Code                      varchar                NOT NULL,
  Support_Refund            varchar,
  Name                      varchar                NOT NULL,
  Is_Active                 tinyint(1) DEFAULT '1' NOT NULL,
  Support_Tokenization      tinyint(1),
  Support_Express_Checkout  tinyint(1),
  Created_At                datetime,
  Updated_At                datetime
);
