CREATE TABLE Custom_Fields
(
  Id                INTEGER                NOT NULL
    PRIMARY KEY AUTOINCREMENT,
  Code              varchar                NOT NULL,
  Name              varchar                NOT NULL,
  Type              varchar                NOT NULL,
  Input_Type        varchar,
  Is_Multiselect    tinyint(1) DEFAULT '0' NOT NULL,
  Datalist          TEXT,
  Options           TEXT,
  Form_Settings     TEXT,
  Use_In_Table      tinyint(1) DEFAULT '0' NOT NULL,
  Table_Settings    TEXT,
  Infolist_Settings TEXT,
  Sort              INTEGER,
  Customizable_Type varchar                NOT NULL,
  Deleted_At        datetime,
  Created_At        datetime,
  Updated_At        datetime
);

CREATE UNIQUE INDEX Custom_Fields_Code_Customizable_Type_Unique
  ON Custom_Fields (Code, Customizable_Type);

CREATE INDEX Custom_Fields_Code_Index
  ON Custom_Fields (Code);

CREATE INDEX Custom_Fields_Sort_Index
  ON Custom_Fields (Sort);
