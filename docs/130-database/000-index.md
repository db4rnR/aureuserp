# Database Documentation

This directory contains database-related files for the AureusERP project, including schemas, diagrams, and SQL scripts.

## Contents

1. [Database Diagram](010-database.diagram) - Source file for the database diagram
2. [Database Diagram (PNG)](020-database.png) - Database diagram in PNG format
3. [Database Diagram (SVG)](030-database.svg) - Database diagram in SVG format
4. [SQLite Schema](040-database-sqlite.sql) - SQL schema for SQLite database
5. [PostgreSQL Schema](050-database-postgresql.sql) - SQL schema for PostgreSQL database
6. [Snowflake Schema](060-database-snowflake.sql) - SQL schema for Snowflake database

## Overview

The AureusERP database is designed to support all aspects of the ERP system, including:

- User management and authentication
- Plugin data storage
- Business entity relationships
- Transactional data
- Configuration and settings

The database schemas are provided in multiple formats to support different deployment environments:

- **SQLite**: Used primarily for development and testing
- **PostgreSQL**: Recommended for production deployments
- **Snowflake**: Available for enterprise deployments with advanced analytics requirements

## Database Structure

The database follows a modular design that aligns with the plugin architecture of AureusERP. Each plugin has its own set of tables with proper foreign key relationships to core tables.

Key table groups include:

- Core system tables (users, permissions, settings)
- Plugin-specific tables (organized by plugin)
- Cross-plugin relationship tables
- Audit and logging tables

## Usage

To use these database schemas:

1. Choose the appropriate schema file for your database system
2. Execute the SQL script to create the database structure
3. Run the AureusERP migrations to ensure all tables are properly initialized
4. Seed the database with initial data if needed

For development environments, the SQLite schema is recommended for simplicity and ease of setup.
