# 1. Cross-Document Navigation Guidelines

**Version:** 1.0.0
**Date:** 2025-05-21
**Author:** Augment Agent
**Status:** Active
**Progress:** 100%

---

<details>
<summary>Table of Contents</summary>

- [1.1. Overview](#11-overview)
- [1.2. Navigation Components](#12-navigation-components)
  - [1.2.1. Previous/Next Navigation](#121-previousnext-navigation)
  - [1.2.2. Breadcrumb Navigation](#122-breadcrumb-navigation)
  - [1.2.3. See Also Section](#123-see-also-section)
  - [1.2.4. Related Documents Section](#124-related-documents-section)
- [1.3. Implementation Guidelines](#13-implementation-guidelines)
  - [1.3.1. HTML/CSS Implementation](#131-htmlcss-implementation)
  - [1.3.2. Markdown Implementation](#132-markdown-implementation)
- [1.4. Examples](#14-examples)
  - [1.4.1. Implementation Guide Example](#141-implementation-guide-example)
  - [1.4.2. Reference Document Example](#142-reference-document-example)
- [1.5. Related Documents](#15-related-documents)
- [1.6. Version History](#16-version-history)

</details>

## 1.1. Overview

This document provides guidelines and templates for implementing consistent cross-document navigation throughout the ELA documentation. The goal is to improve user experience by making it easier to navigate between related documents and find relevant information.

## 1.2. Navigation Components

### 1.2.1. Previous/Next Navigation

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Previous/Next Navigation</h4>

<p>The Previous/Next navigation should be placed at the bottom of each document to allow users to navigate sequentially through related documents.</p>

<h5 style="color: #111;">Template:</h5>

```markdown
---

**Previous:** <a href="./path-to-previous-document.md">Document Title</a> | **Next:** <a href="./path-to-next-document.md">Document Title</a>
```

<h5 style="color: #111;">Styled Version:</h5>

<div style="background-color: #ffffff; padding: 10px; border-radius: 5px; border-top: 1px solid #ddd; display: flex; justify-content: space-between;">
  <div>
    <span style="font-weight: bold;">← Previous:</span>
    <a href="#">Document Title</a>
  </div>
  <div>
    <span style="font-weight: bold;">Next: →</span>
    <a href="#">Document Title</a>
  </div>
</div>
</div>

### 1.2.2. Breadcrumb Navigation

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Breadcrumb Navigation</h4>

<p>Breadcrumb navigation should be placed at the top of each document to show the document's location in the documentation hierarchy.</p>

<h5 style="color: #111;">Template:</h5>

```markdown
<nav aria-label="Breadcrumb">
  <a href="000-index.md">Home</a> > <a href="./section/000-index.md">Section</a> > <a href="./section/subsection/000-index.md">Subsection</a> > Document Title
</nav>

---
```

<h5 style="color: #111;">Styled Version:</h5>

<div style="background-color: #ffffff; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
  <div style="font-size: 0.9em; color: #444;">
    <a href="#">Home</a> &gt;
    <a href="#">Section</a> &gt;
    <a href="#">Subsection</a> &gt;
    <span style="color: #111;">Document Title</span>
  </div>
</div>
</div>

### 1.2.3. See Also Section

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">See Also Section</h4>

<p>The See Also section should be placed within the content where relevant to provide contextual links to related documents.</p>

<h5 style="color: #111;">Template:</h5>

```markdown
<div class="see-also">

**See Also:**
- <a href="./path-to-related-document-1.md">Related Document 1</a> - Brief description
- <a href="./path-to-related-document-2.md">Related Document 2</a> - Brief description

</div>
```

<h5 style="color: #111;">Styled Version:</h5>

<div style="padding: 15px; border-radius: 5px; border-left: 5px solid #0066cc;">
  <p style="margin-top: 0; font-weight: bold; ">See Also:</p>
  <ul style="margin-bottom: 0;">
    <li><a href="#">Related Document 1</a> - Brief description</li>
    <li><a href="#">Related Document 2</a> - Brief description</li>
  </ul>
</div>
</div>

### 1.2.4. Related Documents Section

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Related Documents Section</h4>

<p>The Related Documents section should be placed near the end of each document, before the Version History section, to provide links to related documents.</p>

<h5 style="color: #111;">Template:</h5>

```markdown
## Related Documents

- <a href="./path-to-related-document-1.md">Related Document 1</a> - Brief description
- <a href="./path-to-related-document-2.md">Related Document 2</a> - Brief description
- <a href="./path-to-related-document-3.md">Related Document 3</a> - Brief description
```

<h5 style="color: #111;">Styled Version:</h5>

<div style="background-color: #ffffff; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
  <h3 style="margin-top: 0; color: #111;">Related Documents</h3>
  <ul style="margin-bottom: 0;">
    <li><a href="#">Related Document 1</a> - Brief description</li>
    <li><a href="#">Related Document 2</a> - Brief description</li>
    <li><a href="#">Related Document 3</a> - Brief description</li>
  </ul>
</div>
</div>

## 1.3. Implementation Guidelines

### 1.3.1. HTML/CSS Implementation

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">HTML/CSS Implementation</h4>

<p>For enhanced styling, you can use HTML and CSS within Markdown documents:</p>

```html
<div style="padding: 15px; border-radius: 5px; border-left: 5px solid #0066cc;">
  <p style="margin-top: 0; font-weight: bold; ">See Also:</p>
  <ul style="margin-bottom: 0;">
    <li><a href="./path-to-related-document-1.md">Related Document 1</a> - Brief description</li>
    <li><a href="./path-to-related-document-2.md">Related Document 2</a> - Brief description</li>
  </ul>
</div>
```

<p>This will create a styled "See Also" box with a blue left border and matching text color.</p>
</div>

### 1.3.2. Markdown Implementation

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Markdown Implementation</h4>

<p>For simpler implementation, you can use plain Markdown:</p>

```markdown
> **See Also:**
> - <a href="./path-to-related-document-1.md">Related Document 1</a> - Brief description
> - <a href="./path-to-related-document-2.md">Related Document 2</a> - Brief description
```

<p>This will create a blockquote with the "See Also" text and links.</p>
</div>

## 1.4. Examples

### 1.4.1. Implementation Guide Example

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Implementation Guide Example</h4>

<p>Here's an example of navigation elements in an implementation guide:</p>

```markdown
<nav aria-label="Breadcrumb">
  <a href="000-index.md">Home</a> > <a href="./100-implementation-plan/000-index.md">Implementation Plan</a> > <a href="./100-implementation-plan/100-350-event-sourcing/000-index.md">Event Sourcing</a> > Aggregates
</nav>

---

# Aggregates Implementation Guide

**Version:** 1.0.0
**Date:** 2025-05-21
**Author:** Author Name
**Status:** Complete
**Progress:** 100%

---

<details>
<summary>Table of Contents</summary>
...
</details>

## Overview

...

<div style="padding: 15px; border-radius: 5px; border-left: 5px solid #0066cc;">
  <p style="margin-top: 0; font-weight: bold; ">See Also:</p>
  <ul style="margin-bottom: 0;">
    <li><a href="./030-projectors.md">Projectors Implementation Guide</a> - Learn how to implement projectors for your aggregates</li>
    <li><a href="./040-reactors.md">Reactors Implementation Guide</a> - Learn how to implement reactors for your aggregates</li>
  </ul>
</div>

...

## Related Documents

- [010-overview.md](./010-overview.md) - Event Sourcing Overview
- [030-projectors.md](./030-projectors.md) - Projectors Implementation Guide
- [040-reactors.md](./040-reactors.md) - Reactors Implementation Guide
- [050-implementation.md](./050-implementation.md) - Event Sourcing Implementation

## Version History

...

---

**Previous:** [Event Sourcing Overview](./010-overview.md) | **Next:** [Projectors Implementation Guide](./030-projectors.md)
```
</div>

### 1.4.2. Reference Document Example

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Reference Document Example</h4>

<p>Here's an example of navigation elements in a reference document:</p>

```markdown
<nav aria-label="Breadcrumb">
  <a href="000-index.md">Home</a> > <a href="./reference/000-index.md">Reference Documents</a> > Glossary
</nav>

---

# Glossary

**Version:** 1.0.0
**Date:** 2025-05-21
**Author:** Author Name
**Status:** Complete
**Progress:** 100%

---

<details>
<summary>Table of Contents</summary>
...
</details>

## Overview

...

## Terms

...

## Related Documents

- <a href="./210-ela-coding-standards.md">210-ela-coding-standards.md</a> - Coding standards and best practices
- <a href="./220-ela-documentation-style-guide.md">220-ela-documentation-style-guide.md</a> - Documentation style guide

## Version History

...
```
</div>

## 1.5. Related Documents

- [../000-index.md](../000-index.md) - Main documentation index
- [./000-index.md](000-index.md) - Documentation standards index
- [./010-high-contrast-guidelines.md](010-high-contrast-guidelines.md) - High contrast guidelines
- [./020-diagram-accessibility-guidelines.md](020-diagram-accessibility-guidelines.md) - Diagram accessibility guidelines
- [./030-date-version-formatting-standards.md](030-date-version-formatting-standards.md) - Date and version formatting standards
- [../220-ela-documentation-style-guide-consolidated.md](../220-ela-documentation-style-guide-consolidated.md) - Documentation style guide
- [../230-documentation-roadmap.md](../230-documentation-roadmap.md) - Documentation roadmap

## 1.6. Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-21 | Initial version | Augment Agent |
