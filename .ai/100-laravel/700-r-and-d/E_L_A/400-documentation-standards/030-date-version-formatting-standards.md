# 1. Date and Version Formatting Standards

**Version:** 1.0.0
**Date:** 2025-05-21
**Author:** Augment Agent
**Status:** Active
**Progress:** 100%

---

<details>
<summary>Table of Contents</summary>

- [1.1. Overview](#11-overview)
- [1.2. Date Formatting](#12-date-formatting)
  - [1.2.1. Standard Date Format](#121-standard-date-format)
  - [1.2.2. Date Format in Metadata](#122-date-format-in-metadata)
  - [1.2.3. Date Format in Content](#123-date-format-in-content)
  - [1.2.4. Date Format in File Names](#124-date-format-in-file-names)
- [1.3. Version Formatting](#13-version-formatting)
  - [1.3.1. Semantic Versioning](#131-semantic-versioning)
  - [1.3.2. Version Format in Metadata](#132-version-format-in-metadata)
  - [1.3.3. Version Format in Content](#133-version-format-in-content)
  - [1.3.4. Version Format in File Names](#134-version-format-in-file-names)
- [1.4. Version History Tables](#14-version-history-tables)
  - [1.4.1. Standard Format](#141-standard-format)
  - [1.4.2. Change Description Guidelines](#142-change-description-guidelines)
- [1.5. Implementation Guidelines](#15-implementation-guidelines)
  - [1.5.1. Updating Existing Documents](#151-updating-existing-documents)
  - [1.5.2. Creating New Documents](#152-creating-new-documents)
- [1.6. Examples](#16-examples)
  - [1.6.1. Metadata Example](#161-metadata-example)
  - [1.6.2. Version History Example](#162-version-history-example)
  - [1.6.3. Content References Example](#163-content-references-example)
- [1.7. Related Documents](#17-related-documents)
- [1.8. Version History](#18-version-history)

</details>

## 1.1. Overview

This document defines standards for date and version formatting across all ELA documentation. Consistent date and version formatting improves readability, searchability, and maintainability of documentation.

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Why Standardization Matters</h4>

<p>Consistent date and version formatting provides several benefits:</p>

<ul style="margin-bottom: 0;">
  <li>Improves readability and reduces cognitive load</li>
  <li>Enables chronological sorting and filtering</li>
  <li>Facilitates automated processing and validation</li>
  <li>Ensures clear communication about document currency</li>
  <li>Supports internationalization and localization</li>
</ul>
</div>

## 1.2. Date Formatting

### 1.2.1. Standard Date Format

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Standard Date Format</h4>

<p>All dates in ELA documentation must use the ISO 8601 format:</p>

<div style="background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd; margin-bottom: 10px;">
  <code>YYYY-MM-DD</code>
</div>

<p>Where:</p>
<ul style="margin-bottom: 0;">
  <li><strong>YYYY</strong> = Four-digit year (e.g., 2025)</li>
  <li><strong>MM</strong> = Two-digit month (01-12)</li>
  <li><strong>DD</strong> = Two-digit day (01-31)</li>
</ul>

<p style="margin-top: 10px;">Examples:</p>
<ul style="margin-bottom: 0;">
  <li><code>2025-05-21</code> (May 21, 2025)</li>
  <li><code>2025-01-01</code> (January 1, 2025)</li>
  <li><code>2025-12-31</code> (December 31, 2025)</li>
</ul>
</div>

### 1.2.2. Date Format in Metadata

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Date Format in Metadata</h4>

<p>In document metadata, the date should be formatted as follows:</p>

```markdown
**Date:** 2025-05-21
```

<p>The date in metadata represents the last modification date of the document.</p>

<div style="display: flex; justify-content: space-between; margin-top: 10px;">
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #cc0000;">Incorrect:</p>
    <p style="margin-bottom: 0;"><code>**Date:** 05/21/2025</code></p>
    <p style="margin-bottom: 0;"><code>**Date:** 21-05-2025</code></p>
    <p style="margin-bottom: 0;"><code>**Date:** May 21, 2025</code></p>
  </div>
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #007700;">Correct:</p>
    <p style="margin-bottom: 0;"><code>**Date:** 2025-05-21</code></p>
  </div>
</div>
</div>

### 1.2.3. Date Format in Content

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Date Format in Content</h4>

<p>When referring to dates within document content:</p>

<ul style="margin-bottom: 0;">
  <li>Use the ISO 8601 format (YYYY-MM-DD) for technical references, logs, and tables</li>
  <li>You may use a more readable format (e.g., "May 21, 2025") in narrative text, but be consistent</li>
  <li>When using a more readable format, include the ISO format in parentheses for clarity: "May 21, 2025 (2025-05-21)"</li>
</ul>

<div style="display: flex; justify-content: space-between; margin-top: 10px;">
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #cc0000;">Inconsistent:</p>
    <p style="margin-bottom: 0;">The feature was released on 05/21/25 and updated on June 15th, 2025.</p>
  </div>
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #007700;">Consistent:</p>
    <p style="margin-bottom: 0;">The feature was released on 2025-05-21 and updated on 2025-06-15.</p>
  </div>
</div>
</div>

### 1.2.4. Date Format in File Names

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Date Format in File Names</h4>

<p>When including dates in file names:</p>

<ul style="margin-bottom: 0;">
  <li>Use the ISO 8601 format (YYYY-MM-DD)</li>
  <li>Place the date at the beginning of the file name</li>
  <li>Separate the date from the rest of the file name with a hyphen</li>
</ul>

<div style="display: flex; justify-content: space-between; margin-top: 10px;">
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #cc0000;">Incorrect:</p>
    <p style="margin-bottom: 0;"><code>report_05_21_25.md</code></p>
    <p style="margin-bottom: 0;"><code>21-05-2025-report.md</code></p>
  </div>
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #007700;">Correct:</p>
    <p style="margin-bottom: 0;"><code>2025-05-21-report.md</code></p>
  </div>
</div>
</div>

## 1.3. Version Formatting

### 1.3.1. Semantic Versioning

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Semantic Versioning</h4>

<p>All ELA documentation must use semantic versioning (SemVer) for version numbers:</p>

<div style="background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd; margin-bottom: 10px;">
  <code>MAJOR.MINOR.PATCH</code>
</div>

<p>Where:</p>
<ul style="margin-bottom: 0;">
  <li><strong>MAJOR</strong> = Significant changes that may require updates to related documents</li>
  <li><strong>MINOR</strong> = New content or substantial revisions that don't break existing references</li>
  <li><strong>PATCH</strong> = Small corrections, typo fixes, or clarifications</li>
</ul>

<p style="margin-top: 10px;">Examples:</p>
<ul style="margin-bottom: 0;">
  <li><code>1.0.0</code> = Initial release</li>
  <li><code>1.1.0</code> = Added new section</li>
  <li><code>1.1.1</code> = Fixed typos</li>
  <li><code>2.0.0</code> = Major restructuring</li>
</ul>
</div>

### 1.3.2. Version Format in Metadata

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Version Format in Metadata</h4>

<p>In document metadata, the version should be formatted as follows:</p>

```markdown
**Version:** 1.0.0
```

<div style="display: flex; justify-content: space-between; margin-top: 10px;">
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #cc0000;">Incorrect:</p>
    <p style="margin-bottom: 0;"><code>**Version:** 1</code></p>
    <p style="margin-bottom: 0;"><code>**Version:** v1.0</code></p>
    <p style="margin-bottom: 0;"><code>**Version:** Version 1.0.0</code></p>
  </div>
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #007700;">Correct:</p>
    <p style="margin-bottom: 0;"><code>**Version:** 1.0.0</code></p>
  </div>
</div>
</div>

### 1.3.3. Version Format in Content

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Version Format in Content</h4>

<p>When referring to versions within document content:</p>

<ul style="margin-bottom: 0;">
  <li>Use the full semantic version (MAJOR.MINOR.PATCH) for precise references</li>
  <li>You may use partial versions (e.g., "version 2.1") in narrative text when the exact patch level is not important</li>
  <li>When referring to a range of versions, use the format "versions 1.0.0 through 1.2.0" or "versions 1.0.0-1.2.0"</li>
</ul>

<div style="display: flex; justify-content: space-between; margin-top: 10px;">
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #cc0000;">Inconsistent:</p>
    <p style="margin-bottom: 0;">This feature was introduced in v1.2 and improved in version 1.3.0.</p>
  </div>
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #007700;">Consistent:</p>
    <p style="margin-bottom: 0;">This feature was introduced in version 1.2.0 and improved in version 1.3.0.</p>
  </div>
</div>
</div>

### 1.3.4. Version Format in File Names

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Version Format in File Names</h4>

<p>When including versions in file names:</p>

<ul style="margin-bottom: 0;">
  <li>Use the format "v" followed by the semantic version (vMAJOR.MINOR.PATCH)</li>
  <li>Place the version after the document name</li>
  <li>Separate the version from the document name with a hyphen</li>
</ul>

<div style="display: flex; justify-content: space-between; margin-top: 10px;">
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #cc0000;">Incorrect:</p>
    <p style="margin-bottom: 0;"><code>documentation_1.0.0.md</code></p>
    <p style="margin-bottom: 0;"><code>v1.0.0-documentation.md</code></p>
  </div>
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #007700;">Correct:</p>
    <p style="margin-bottom: 0;"><code>documentation-v1.0.0.md</code></p>
  </div>
</div>
</div>

## 1.4. Version History Tables

### 1.4.1. Standard Format

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Standard Format</h4>

<p>All documents must include a version history table at the end with the following columns:</p>

<ul style="margin-bottom: 0;">
  <li><strong>Version:</strong> The semantic version number</li>
  <li><strong>Date:</strong> The date of the version in ISO 8601 format (YYYY-MM-DD)</li>
  <li><strong>Changes:</strong> A brief description of the changes</li>
  <li><strong>Author:</strong> The name of the author who made the changes</li>
</ul>

<p style="margin-top: 10px;">Example:</p>

```markdown
## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-01 | Initial version | John Doe |
| 1.0.1 | 2025-05-05 | Fixed typos in section 3 | Jane Smith |
| 1.1.0 | 2025-05-15 | Added new section on accessibility | John Doe |
| 2.0.0 | 2025-05-21 | Major restructuring of content | Jane Smith |
```
</div>

### 1.4.2. Change Description Guidelines

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Change Description Guidelines</h4>

<p>When describing changes in the version history table:</p>

<ul style="margin-bottom: 0;">
  <li>Use concise, specific descriptions</li>
  <li>Start with a past-tense verb (e.g., "Added," "Fixed," "Updated," "Removed")</li>
  <li>Mention the specific section or feature that was changed</li>
  <li>For major changes, provide a brief summary of the impact</li>
</ul>

<div style="display: flex; justify-content: space-between; margin-top: 10px;">
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #cc0000;">Poor Descriptions:</p>
    <p style="margin-bottom: 0;">"Updates"</p>
    <p style="margin-bottom: 0;">"Fixed bugs"</p>
    <p style="margin-bottom: 0;">"Made changes to section 3"</p>
  </div>
  <div style="width: 45%; background-color: #ffffff; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
    <p style="margin-top: 0; font-weight: bold; color: #007700;">Good Descriptions:</p>
    <p style="margin-bottom: 0;">"Added new section on accessibility"</p>
    <p style="margin-bottom: 0;">"Fixed broken links in implementation guide"</p>
    <p style="margin-bottom: 0;">"Updated diagrams with high-contrast colors"</p>
  </div>
</div>
</div>

## 1.5. Implementation Guidelines

### 1.5.1. Updating Existing Documents

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Updating Existing Documents</h4>

<p>When updating existing documents:</p>

<ol style="margin-bottom: 0;">
  <li>Update the version number in the metadata according to semantic versioning rules</li>
  <li>Update the date in the metadata to the current date</li>
  <li>Add a new entry to the version history table</li>
  <li>If the document status has changed, update the status in the metadata</li>
</ol>
</div>

### 1.5.2. Creating New Documents

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Creating New Documents</h4>

<p>When creating new documents:</p>

<ol style="margin-bottom: 0;">
  <li>Set the initial version to 1.0.0</li>
  <li>Set the date to the current date in ISO 8601 format</li>
  <li>Create a version history table with the initial entry</li>
  <li>Set the appropriate status (e.g., "New," "Draft")</li>
</ol>
</div>

## 1.6. Examples

### 1.6.1. Metadata Example

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Metadata Example</h4>

```markdown
# Document Title

**Version:** 1.2.0
**Date:** 2025-05-21
**Author:** John Doe
**Status:** Updated
**Progress:** Complete

---
```
</div>

### 1.6.2. Version History Example

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Version History Example</h4>

```markdown
## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-01 | Initial version | John Doe |
| 1.0.1 | 2025-05-05 | Fixed typos in section 3 | Jane Smith |
| 1.1.0 | 2025-05-15 | Added new section on accessibility | John Doe |
| 1.2.0 | 2025-05-21 | Updated diagrams with high-contrast colors | Jane Smith |
```
</div>

### 1.6.3. Content References Example

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Content References Example</h4>

```markdown
## Feature Timeline

The event sourcing feature was first introduced in version 1.0.0 (2025-01-15) with basic functionality. Version 1.2.0 (2025-03-10) added support for snapshots, and version 2.0.0 (2025-05-01) introduced a completely redesigned API.

For more information, see the release notes for versions 1.0.0 through 2.0.0.
```
</div>

## 1.7. Related Documents

- [../000-index.md](../000-index.md) - Main documentation index
- [./000-index.md](000-index.md) - Documentation standards index
- [./010-high-contrast-guidelines.md](010-high-contrast-guidelines.md) - High contrast guidelines
- [./020-diagram-accessibility-guidelines.md](020-diagram-accessibility-guidelines.md) - Diagram accessibility guidelines
- [../220-ela-documentation-style-guide-consolidated.md](../220-ela-documentation-style-guide-consolidated.md) - Documentation style guide
- [../230-documentation-roadmap.md](../230-documentation-roadmap.md) - Documentation roadmap

## 1.8. Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-21 | Initial version | Augment Agent |
