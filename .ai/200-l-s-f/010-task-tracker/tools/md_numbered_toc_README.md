# Markdown Processing Tools

Two Python utilities for processing markdown files with hierarchical header numbering and table of contents generation. **Both scripts work exclusively with heading levels 2-6 (## through ######), completely ignoring H1 headers.**

## Scripts

### 1. md_numbered_headers.py

Adds hierarchical numbering to markdown headers while preserving the original file structure.

**Features:**
- **Processes only H2-H6 headers** (## through ######)
- **Completely ignores H1 headers** (# Document titles)
- Proper UTF-8 encoding support
- Comprehensive error handling
- Can output to file or stdout

**Usage:**
```bash
python md_numbered_headers.py input.md [output.md]
```

**Example:**
```markdown
# Document Title          # Document Title (ignored)
## Introduction      →    ## 1. Introduction  
### Background            ### 1.1. Background
## Main Content           ## 2. Main Content
```

### 2. md_toc.py

Generates a hierarchical table of contents from markdown headers with GitHub-compatible anchor links.

**Features:**
- **Processes only H2-H6 headers** (## through ######)
- **Completely ignores H1 headers** (# Document titles)
- Generates proper GitHub-style anchor links
- Hierarchical numbering with proper indentation
- Unicode normalization for anchor generation
- Configurable max depth (levels 1-5, corresponding to H2-H6)

**Usage:**
```bash
python md_toc.py input.md [output.md]
```

**Example Output:**
```markdown
## Table of Contents

- [1. Introduction](#1-introduction)
  - [1.1. Background](#11-background)
    - [1.1.1. Historical Context](#111-historical-context)
- [2. Main Content](#2-main-content)
```

## Improvements Made

### 1. Error Handling
- File existence validation
- Permission error handling
- UTF-8 encoding error handling
- Proper exit codes

### 2. Code Quality
- Type hints throughout
- Comprehensive docstrings
- Object-oriented design
- Regular expression patterns for robust header detection

### 3. Functionality Enhancements
- **Exclusive H2-H6 processing** (no longer processes H1 headers)
- Support for all header levels from ## through ######
- GitHub-compatible anchor generation
- Proper Unicode handling
- Configurable options (max depth for TOC)

### 4. Usability
- Clear usage messages
- Progress feedback
- Both file and stdout output options
- Proper CLI argument handling

## Configuration Options

Both scripts can be easily modified for different behaviors:

- **Max Depth**: Set `max_depth=3` in TOC generator to limit depth to H2-H4
- **Anchor Style**: Modify `_slugify()` method for different anchor formats

**Note:** Both scripts are hardcoded to ignore H1 headers completely. If you need H1 processing, modify the regex pattern from `r'^(#{2,6})\s+(.+)$'` to `r'^(#{1,6})\s+(.+)$'` and adjust the level calculations accordingly.

## Dependencies

- Python 3.6+
- Standard library only (no external packages required)

## Error Handling

The scripts handle common error scenarios:
- Missing input files
- Permission denied
- Invalid UTF-8 encoding
- Invalid file paths
- Directory instead of file

All errors provide clear, actionable error messages.
