CREATE TABLE Password_Reset_Tokens
(
  Email      varchar NOT NULL
    PRIMARY KEY,
  Token      varchar NOT NULL,
  Created_At datetime
);
