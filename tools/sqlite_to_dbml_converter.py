from enum import Enum, StrEnum, auto
from typing import Dict, Optional, List
import sqlite3
import re
from pathlib import Path

class LogLevel(StrEnum):
    """Enum for logging levels"""
    INFO = "INFO"
    DEBUG = "DEBUG"
    WARNING = "WARNING"
    ERROR = "ERROR"

class SqliteType(Enum):
    """SQLite data types with their DBML equivalents"""
    INTEGER = "integer"
    INT = "integer"
    TINYINT = "integer"
    SMALLINT = "integer"
    MEDIUMINT = "integer"
    BIGINT = "integer"
    UNSIGNED_BIG_INT = "integer"
    INT2 = "integer"
    INT8 = "integer"
    REAL = "real"
    DOUBLE = "real"
    DOUBLE_PRECISION = "real"
    FLOAT = "real"
    NUMERIC = "decimal"
    DECIMAL = "decimal"
    BOOLEAN = "boolean"
    DATE = "date"
    DATETIME = "datetime"
    TIMESTAMP = "timestamp"
    TIME = "time"
    TEXT = "text"
    CLOB = "text"
    VARCHAR = "varchar"
    VARYING_CHARACTER = "varchar"
    NCHAR = "varchar"
    NATIVE_CHARACTER = "varchar"
    NVARCHAR = "varchar"
    CHAR = "varchar"
    CHARACTER = "varchar"
    BLOB = "text"

class DbmlKeyword(StrEnum):
    """DBML reserved keywords that need escaping"""
    TABLE = "table"
    REF = "ref"
    ENUM = "enum"
    PROJECT = "project"
    NOTE = "note"
    INDEXES = "indexes"
    INDEX = "index"
    PK = "pk"
    UNIQUE = "unique"
    NOT = "not"
    NULL = "null"
    INCREMENT = "increment"
    DEFAULT = "default"

class SqlFunction(StrEnum):
    """SQL functions that should be wrapped in backticks"""
    CURRENT_TIMESTAMP = "CURRENT_TIMESTAMP"
    CURRENT_DATE = "CURRENT_DATE"
    CURRENT_TIME = "CURRENT_TIME"

class BooleanValue(StrEnum):
    """Boolean values in various formats"""
    TRUE = "true"
    FALSE = "false"
    ONE = "1"
    ZERO = "0"

class SqliteToDbmlConverter:
    def __init__(self, verbose: bool = False):
        self.verbose = verbose
        self._has_unique_column: Optional[bool] = None

    def _log(self, message: str, level: LogLevel = LogLevel.INFO) -> None:
        """Log a message with timestamp and level"""
        from datetime import datetime
        timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        print(f"{timestamp} - {level.value} - {message}")

    def _log_debug(self, message: str) -> None:
        if self.verbose:
            self._log(message, LogLevel.DEBUG)

    def _log_warning(self, message: str) -> None:
        self._log(message, LogLevel.WARNING)

    def _log_error(self, message: str) -> None:
        self._log(message, LogLevel.ERROR)

    def _should_escape_identifier(self, identifier: str) -> bool:
        """Check if an identifier needs escaping based on DBML keywords"""
        return (
            identifier.lower() in [kw.value for kw in DbmlKeyword] or
            ' ' in identifier or
            '-' in identifier or
            identifier.startswith('"') or
            not identifier.replace('_', '').replace('-', '').isalnum()
        )

    def _escape_identifier(self, identifier: str) -> str:
        """Escape identifier if needed"""
        if self._should_escape_identifier(identifier) and not (
            identifier.startswith('"') and identifier.endswith('"')
        ):
            return f'"{identifier}"'
        return identifier

    def _sqlite_to_dbml_type(self, sqlite_type: str) -> str:
        """Convert SQLite type to DBML type using enum"""
        normalized_type = sqlite_type.upper().replace(' ', '_')

        # Try to find exact match
        for sql_type in SqliteType:
            if sql_type.name == normalized_type:
                return sql_type.value

        # Fallback: extract base type (before parentheses or spaces)
        base_type = re.split(r'[(\s]', sqlite_type.upper())[0]
        base_type_normalized = base_type.replace(' ', '_')

        for sql_type in SqliteType:
            if sql_type.name == base_type_normalized:
                return sql_type.value

        # If no match found, return lowercase original
        return sqlite_type.lower()

    def _format_default_value(self, default_value: Optional[str]) -> str:
        """Format default value for DBML"""
        if default_value is None:
            return ''

        # Handle string defaults
        if default_value.startswith("'") and default_value.endswith("'"):
            return f"'{default_value[1:-1]}'"

        # Handle numeric defaults
        if default_value.replace('.', '').replace('-', '').isdigit():
            return default_value

        # Handle boolean defaults
        if default_value.lower() in [val.value for val in BooleanValue]:
            return default_value.lower()

        # Handle SQL functions
        if default_value.upper() in [func.value for func in SqlFunction]:
            return f"`{default_value}`"

        # For other cases, quote the value
        return f"'{default_value}'"
