CREATE TABLE Time_Off_Leave_Allocations
(
  Id                                INTEGER  NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Holiday_Status_Id                 INTEGER  NOT NULL
    REFERENCES Time_Off_Leave_Types
      ON DELETE RESTRICT,
  Employee_Id                       INTEGER  NOT NULL
    REFERENCES Employees_Employees
      ON DELETE RESTRICT,
  Employee_Company_Id               INTEGER
                                             REFERENCES Companies
                                               ON DELETE SET NULL,
  Manager_Id                        INTEGER
                                             REFERENCES Employees_Employees
                                               ON DELETE SET NULL,
  Approver_Id                       INTEGER
                                             REFERENCES Employees_Employees
                                               ON DELETE SET NULL,
  Second_Approver_Id                INTEGER
                                             REFERENCES Employees_Employees
                                               ON DELETE SET NULL,
  Department_Id                     INTEGER
                                             REFERENCES Employees_Departments
                                               ON DELETE SET NULL,
  Accrual_Plan_Id                   INTEGER
                                             REFERENCES Time_Off_Leave_Accrual_Plans
                                               ON DELETE SET NULL,
  Creator_Id                        INTEGER
                                             REFERENCES Users
                                               ON DELETE SET NULL,
  Name                              varchar,
  State                             varchar DEFAULT 'confirm',
  Allocation_Type                   varchar DEFAULT 'regular',
  Date_From                         datetime NOT NULL,
  Date_To                           datetime,
  Last_Executed_Carryover_Date      datetime,
  Last_Called                       datetime,
  Actual_Last_Called                datetime,
  Next_Call                         datetime,
  Carried_Over_Days_Expiration_Date datetime,
  Notes                             TEXT,
  Already_Accrued                   tinyint(1),
  Number_Of_Days                    numeric DEFAULT '0',
  Number_Of_Hours_Display           numeric DEFAULT '0',
  Yearly_Accrued_Amount             numeric DEFAULT '0',
  Expiring_Carryover_Days           numeric DEFAULT '0',
  Created_At                        datetime,
  Updated_At                        datetime,
  CHECK ("allocation_type" IN ('regular', 'accrual')),
  CHECK ("state" IN ('confirm', 'refuse', 'validate_one', 'validate_two'))
);
