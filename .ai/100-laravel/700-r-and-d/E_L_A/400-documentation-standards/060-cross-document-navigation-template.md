# 1. Cross-Document Navigation Template

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
- [1.3. Implementation Examples](#13-implementation-examples)
  - [1.3.1. Implementation Guide Example](#131-implementation-guide-example)
  - [1.3.2. Reference Document Example](#132-reference-document-example)
- [1.4. Implementation Checklist](#14-implementation-checklist)
- [1.5. Related Documents](#15-related-documents)
- [1.6. Version History](#16-version-history)

</details>

## 1.1. Overview

This document provides templates for implementing consistent cross-document navigation throughout the ELA documentation. These templates can be copied and adapted for use in your documentation.

## 1.2. Navigation Components

### 1.2.1. Previous/Next Navigation

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Previous/Next Navigation Template</h4>

```markdown
---

**Previous:** <a href="./path-to-previous-document.md">Document Title</a> | **Next:** <a href="./path-to-next-document.md">Document Title</a>
```

<p>Copy this template to the bottom of your document and replace the document titles and paths with the appropriate values.</p>
</div>

### 1.2.2. Breadcrumb Navigation

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Breadcrumb Navigation Template</h4>

```markdown
<nav aria-label="Breadcrumb">
  <a href="000-index.md">Home</a> > <a href="./section/000-index.md">Section</a> > <a href="./section/subsection/000-index.md">Subsection</a> > Document Title
</nav>

---
```

<p>Copy this template to the top of your document and replace the section names and paths with the appropriate values.</p>
</div>

### 1.2.3. See Also Section

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">See Also Section Template (HTML/CSS)</h4>

```html
<div style="padding: 15px; border-radius: 5px; border-left: 5px solid #0066cc;">
  <p style="margin-top: 0; font-weight: bold; ">See Also:</p>
  <ul style="margin-bottom: 0;">
    <li><a href="./path-to-related-document-1.md">Related Document 1</a> - Brief description</li>
    <li><a href="./path-to-related-document-2.md">Related Document 2</a> - Brief description</li>
  </ul>
</div>
```

<h4 style="margin-top: 15px; ">See Also Section Template (Markdown)</h4>

```markdown
> **See Also:**
> - [Related Document 1](./path-to-related-document-1.md) - Brief description
> - [Related Document 2](./path-to-related-document-2.md) - Brief description
```

<p>Copy one of these templates to your document where you want to reference related documents.</p>
</div>

### 1.2.4. Related Documents Section

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Related Documents Section Template</h4>

```markdown
## Related Documents

- <a href="./path-to-related-document-1.md">Related Document 1</a> - Brief description
- <a href="./path-to-related-document-2.md">Related Document 2</a> - Brief description
- <a href="./path-to-related-document-3.md">Related Document 3</a> - Brief description
```

<p>Copy this template to your document before the Version History section and replace the document titles and paths with the appropriate values.</p>
</div>

## 1.3. Implementation Examples

### 1.3.1. Implementation Guide Example

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Implementation Guide Example</h4>

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

### 1.3.2. Reference Document Example

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Reference Document Example</h4>

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

## 1.4. Implementation Checklist

<div style="padding: 15px; border-radius: 5px; border: 1px solid #b0c4de; margin-bottom: 20px;">
<h4 style="margin-top: 0; ">Implementation Checklist</h4>

<ul style="margin-bottom: 0;">
  <li><input type="checkbox" disabled> Add breadcrumb navigation to all documents</li>
  <li><input type="checkbox" disabled> Add Previous/Next navigation to all implementation guides</li>
  <li><input type="checkbox" disabled> Add See Also sections where relevant</li>
  <li><input type="checkbox" disabled> Add Related Documents section to all documents</li>
  <li><input type="checkbox" disabled> Verify all links are valid</li>
  <li><input type="checkbox" disabled> Ensure consistent styling across all documents</li>
  <li><input type="checkbox" disabled> Test navigation with users</li>
</ul>
</div>

## 1.5. Related Documents

- [../000-index.md](../000-index.md) - Main documentation index
- [./000-index.md](000-index.md) - Documentation standards index
- [./010-high-contrast-guidelines.md](010-high-contrast-guidelines.md) - High contrast guidelines
- [./020-diagram-accessibility-guidelines.md](020-diagram-accessibility-guidelines.md) - Diagram accessibility guidelines
- [./030-date-version-formatting-standards.md](030-date-version-formatting-standards.md) - Date and version formatting standards
- [./040-cross-document-navigation-guidelines.md](040-cross-document-navigation-guidelines.md) - Cross-document navigation guidelines
- [./050-documentation-index-template.md](050-documentation-index-template.md) - Documentation index template
- [../220-ela-documentation-style-guide-consolidated.md](../220-ela-documentation-style-guide-consolidated.md) - Documentation style guide
- [../230-documentation-roadmap.md](../230-documentation-roadmap.md) - Documentation roadmap

## 1.6. Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-21 | Initial version | Augment Agent |
