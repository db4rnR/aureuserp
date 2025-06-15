CREATE TABLE Time_Off_Leave_Types
(
  Id                                  INTEGER               NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Sort                                INTEGER,
  Color                               varchar,
  Company_Id                          INTEGER
                                                            REFERENCES Companies
                                                              ON DELETE SET NULL,
  Max_Allowed_Negative                INTEGER,
  Creator_Id                          INTEGER
                                                            REFERENCES Users
                                                              ON DELETE SET NULL,
  Leave_Validation_Type               varchar DEFAULT 'hr',
  Requires_Allocation                 varchar DEFAULT 'no'  NOT NULL,
  Employee_Requests                   varchar DEFAULT 'no'  NOT NULL,
  Allocation_Validation_Type          varchar DEFAULT 'hr',
  Time_Type                           varchar DEFAULT 'leave',
  Request_Unit                        varchar DEFAULT 'day' NOT NULL,
  Name                                varchar               NOT NULL,
  Create_Calendar_Meeting             tinyint(1),
  Is_Active                           tinyint(1),
  Show_On_Dashboard                   tinyint(1),
  Unpaid                              tinyint(1),
  Include_Public_Holidays_In_Duration tinyint(1),
  Support_Document                    tinyint(1),
  Allows_Negative                     tinyint(1),
  Deleted_At                          datetime,
  Created_At                          datetime,
  Updated_At                          datetime,
  CHECK ("allocation_validation_type" IN ('no_validation', 'hr', 'manager', 'both')),
  CHECK ("employee_requests" IN ('yes', 'no')),
  CHECK ("leave_validation_type" IN ('no_validation', 'hr', 'manager', 'both')),
  CHECK ("request_unit" IN ('day', 'half_day', 'hour')),
  CHECK ("requires_allocation" IN ('yes', 'no')),
  CHECK ("time_type" IN ('leave', 'other'))
);
