CREATE TABLE Time_Off_Leave_Accrual_Levels
(
  Id                          INTEGER                 NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                        INTEGER,
  Accrual_Plan_Id             INTEGER                 NOT NULL
    REFERENCES Time_Off_Leave_Accrual_Plans
      ON DELETE CASCADE,
  Start_Count                 INTEGER,
  First_Day                   INTEGER,
  Second_Day                  INTEGER,
  First_Month_Day             INTEGER,
  Second_Month_Day            INTEGER,
  Yearly_Day                  INTEGER,
  Postpone_Max_Days           INTEGER,
  Accrual_Validity_Count      INTEGER,
  Creator_Id                  INTEGER
                                                      REFERENCES Users
                                                        ON DELETE SET NULL,
  Start_Type                  varchar DEFAULT 'days'  NOT NULL,
  Added_Value_Type            varchar DEFAULT 'days'  NOT NULL,
  Frequency                   varchar DEFAULT 'daily' NOT NULL,
  Week_Day                    varchar,
  First_Month                 varchar,
  Second_Month                varchar,
  Yearly_Month                varchar,
  Action_With_Unused_Accruals varchar DEFAULT 'lost'  NOT NULL,
  Accrual_Validity_Type       varchar DEFAULT 'days',
  Added_Value                 INTEGER                 NOT NULL,
  Maximum_Leave               INTEGER,
  Maximum_Leave_Yearly        INTEGER,
  Cap_Accrued_Time            tinyint(1),
  Cap_Accrued_Time_Yearly     tinyint(1),
  Accrual_Validity            tinyint(1),
  Created_At                  datetime,
  Updated_At                  datetime,
  CHECK ("accrual_validity_type" IN ('days', 'months')),
  CHECK ("action_with_unused_accruals" IN ('lost', 'all', 'maximum')),
  CHECK ("added_value_type" IN ('days', 'hours')),
  CHECK ("frequency" IN ('hourly', 'daily', 'weekly', 'bimonthly', 'monthly', 'biyearly', 'yearly')),
  CHECK ("start_type" IN ('months', 'days', 'years')),
  CHECK ("week_day" IN ('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'))
);
