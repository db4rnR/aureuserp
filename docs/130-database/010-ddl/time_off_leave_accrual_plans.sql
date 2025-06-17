CREATE TABLE Time_Off_Leave_Accrual_Plans
(
  Id                      INTEGER                       NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Time_Off_Type_Id        INTEGER
    REFERENCES Time_Off_Leave_Types
      ON DELETE CASCADE,
  Company_Id              INTEGER
    REFERENCES Companies
      ON DELETE CASCADE,
  Carryover_Day           INTEGER,
  Creator_Id              INTEGER
    REFERENCES Users
      ON DELETE CASCADE,
  Name                    varchar                       NOT NULL,
  Transition_Mode         varchar DEFAULT 'immediately' NOT NULL,
  Accrued_Gain_Time       varchar DEFAULT 'end'         NOT NULL,
  Carryover_Date          varchar DEFAULT 'year_start'  NOT NULL,
  Carryover_Month         varchar DEFAULT 'jan'         NOT NULL,
  Added_Value_Type        varchar,
  Is_Active               tinyint(1),
  Is_Based_On_Worked_Time tinyint(1),
  Created_At              datetime,
  Updated_At              datetime,
  CHECK ("accrued_gain_time" IN ('start', 'end')),
  CHECK ("carryover_date" IN ('year_start', 'allocation', 'other')),
  CHECK ("carryover_month" IN ('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec')),
  CHECK ("transition_mode" IN ('immediately', 'end_of_accrual'))
);
