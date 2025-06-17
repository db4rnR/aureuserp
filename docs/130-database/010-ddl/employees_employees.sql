CREATE TABLE Employees_Employees
(
  Id                             INTEGER                       NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Time_Zone                      varchar,
  Work_Permit                    varchar,
  Address_Id                     INTEGER,
  Leave_Manager_Id               INTEGER
                                                               REFERENCES Users
                                                                 ON DELETE SET NULL,
  Bank_Account_Id                INTEGER
                                                               REFERENCES Partners_Bank_Accounts
                                                                 ON DELETE SET NULL,
  Private_State_Id               INTEGER
                                                               REFERENCES States
                                                                 ON DELETE SET NULL,
  Private_Country_Id             INTEGER
                                                               REFERENCES Countries
                                                                 ON DELETE SET NULL,
  Company_Id                     INTEGER
                                                               REFERENCES Companies
                                                                 ON DELETE SET NULL,
  User_Id                        INTEGER
    REFERENCES Users
      ON DELETE RESTRICT,
  Creator_Id                     INTEGER
                                                               REFERENCES Users
                                                                 ON DELETE SET NULL,
  Calendar_Id                    INTEGER
                                                               REFERENCES Employees_Calendars
                                                                 ON DELETE SET NULL,
  Department_Id                  INTEGER
                                                               REFERENCES Employees_Departments
                                                                 ON DELETE SET NULL,
  Job_Id                         INTEGER
                                                               REFERENCES Employees_Job_Positions
                                                                 ON DELETE SET NULL,
  Partner_Id                     INTEGER
                                                               REFERENCES Partners_Partners
                                                                 ON DELETE SET NULL,
  Work_Location_Id               INTEGER
                                                               REFERENCES Employees_Work_Locations
                                                                 ON DELETE SET NULL,
  Parent_Id                      INTEGER
                                                               REFERENCES Employees_Employees
                                                                 ON DELETE SET NULL,
  Coach_Id                       INTEGER
                                                               REFERENCES Employees_Employees
                                                                 ON DELETE SET NULL,
  Country_Id                     INTEGER
                                                               REFERENCES Countries
                                                                 ON DELETE SET NULL,
  State_Id                       INTEGER
                                                               REFERENCES States
                                                                 ON DELETE SET NULL,
  Country_Of_Birth               INTEGER
                                                               REFERENCES Countries
                                                                 ON DELETE SET NULL,
  Departure_Reason_Id            INTEGER
    REFERENCES Employees_Departure_Reasons
      ON DELETE RESTRICT,
  Attendance_Manager_Id          INTEGER
                                                               REFERENCES Users
                                                                 ON DELETE SET NULL,
  Name                           varchar,
  Job_Title                      varchar,
  Work_Phone                     varchar,
  Mobile_Phone                   varchar,
  Color                          varchar,
  Children                       INTEGER,
  Distance_Home_Work             INTEGER    DEFAULT '0',
  Km_Home_Work                   INTEGER    DEFAULT '0',
  Distance_Home_Work_Unit        varchar    DEFAULT 'km',
  Work_Email                     varchar,
  Private_Phone                  varchar,
  Private_Email                  varchar,
  Private_Street1                varchar,
  Private_Street2                varchar,
  Private_City                   varchar,
  Private_Zip                    varchar,
  Private_Car_Plate              varchar,
  Lang                           varchar,
  Gender                         varchar,
  Birthday                       varchar,
  Marital                        varchar    DEFAULT 'single'   NOT NULL,
  Spouse_Complete_Name           varchar,
  Spouse_Birthdate               varchar,
  Place_Of_Birth                 varchar,
  Ssnid                          varchar,
  Sinid                          varchar,
  Identification_Id              varchar,
  Passport_Id                    varchar,
  Permit_No                      varchar,
  Visa_No                        varchar,
  Certificate                    varchar,
  Study_Field                    varchar,
  Study_School                   varchar,
  Emergency_Contact              varchar,
  Emergency_Phone                varchar,
  Employee_Type                  varchar    DEFAULT 'employee' NOT NULL,
  Barcode                        varchar,
  Pin                            varchar,
  Visa_Expire                    varchar,
  Work_Permit_Expiration_Date    varchar,
  Departure_Date                 varchar,
  Departure_Description          TEXT,
  Additional_Note                TEXT,
  Notes                          TEXT,
  Is_Active                      tinyint(1) DEFAULT '0'        NOT NULL,
  Is_Flexible                    tinyint(1),
  Is_Fully_Flexible              tinyint(1),
  Work_Permit_Scheduled_Activity tinyint(1),
  Deleted_At                     datetime,
  Created_At                     datetime,
  Updated_At                     datetime
);
