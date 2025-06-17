# 1. High Contrast Guidelines for Documentation

**Version:** 1.0.0
**Date:** 2025-05-21
**Author:** Augment Agent
**Status:** Active
**Progress:** 100%

---

<details>
<summary>Table of Contents</summary>

- [1.1. Overview](#11-overview)
- [1.2. Color Contrast Principles](#12-color-contrast-principles)
- [1.3. Recommended Color Palette](#13-recommended-color-palette)
- [1.4. Typography Guidelines](#14-typography-guidelines)
- [1.5. HTML/CSS Implementation](#15-htmlcss-implementation)
- [1.6. Mermaid Diagram Contrast](#16-mermaid-diagram-contrast)
- [1.7. Custom Instructions for Augment](#17-custom-instructions-for-augment)
- [1.8. Related Documents](#18-related-documents)
- [1.9. Version History](#19-version-history)

</details>

## 1.1. Overview

This document provides guidelines for maintaining high contrast in documentation to improve readability and accessibility. It includes specific recommendations for color combinations, typography, and HTML/CSS implementation, as well as custom instructions for Augment to maintain these standards.

## 1.2. Color Contrast Principles

High contrast documentation follows these key principles:

1. **Minimum Contrast Ratio**: Maintain a minimum contrast ratio of 4.5:1 for normal text and 3:1 for large text (WCAG AA standard).
2. **Dark Text on Light Backgrounds**: Use dark text (#111, #222, #444) on light backgrounds for maximum readability.
3. **Avoid Light Gray Text**: Never use light gray text (#7f8c8d, #aaa) on light backgrounds.
4. **Border Definition**: Add borders to containers to clearly define boundaries.
5. **Background Differentiation**: Use distinct background colors for different sections.
6. **Color Coding**: Use consistent, high-contrast colors for different categories.
7. **Text Enhancement**: Use bold text for important information.

## 1.3. Recommended Color Palette

### 1.3.1. Text Colors

| Purpose | Old Color | New Color | Notes |
|---------|-----------|-----------|-------|
| Primary Text | #333 | #111 | Main body text |
| Secondary Text | #7f8c8d | #444 | Supporting text |
| Headings | #333 | #222 | Section headings |
| Blue Category | #3498db | #0066cc | Documentation Index |
| Green Category | #2ecc71 | #007700 | Cross-Document Navigation |
| Orange Category | #f39c12 | #cc7700 | Diagram Accessibility |
| Purple Category | #9b59b6 | #6600cc | Date and Version Formatting |
| Gray Category | #34495e | #222 | Implementation Planning |
| Success | #27ae60 | #007700 | Completed tasks |
| Warning | #e67e22 | #cc7700 | Medium priority |
| Error | #e74c3c | #cc0000 | High priority |

### 1.3.2. Background Colors

| Purpose | Old Color | New Color | Notes |
|---------|-----------|-----------|-------|
| Main Background | #f8f9fa | #f0f0f0 | Page background |
| Container Background | #fff | #fff | Card background |
| Blue Background | #ebf5fb | #e0e8f0 | Documentation Index |
| Green Background | #eafaf1 | #e0f0e0 | Cross-Document Navigation |
| Orange Background | #fef9e7 | #f0e8d0 | Diagram Accessibility |
| Purple Background | #f4ecf7 | #e8e0f0 | Date and Version Formatting |
| Gray Background | #eaecee | #e0e0e0 | Implementation Planning |
| Error Background | #fadbd8 | #f0d0d0 | Risk assessment |
| Table Header | #ecf0f1 | #d9d9d9 | Table headers |
| Progress Bar Background | #ecf0f1 | #d9d9d9 | Progress bars |

## 1.4. Typography Guidelines

1. **Font Weight**:
   - Use `font-weight: bold` for headings and important text
   - Use `font-weight: 500` for secondary text instead of normal weight

2. **Text Size**:
   - Minimum body text size: 14px
   - Headings: 18px or larger
   - Secondary text: No smaller than 12px

3. **Line Height**:
   - Use a minimum line height of 1.5 for body text
   - Add spacing between list items with `margin-bottom: 5px`

4. **Text Decoration**:
   - Use background highlighting for emphasis in lists
   - Add padding around emphasized text

## 1.5. HTML/CSS Implementation

### 1.5.1. Container Styling

```css
/* High-contrast container */
.high-contrast-container {
  background-color: #f0f0f0;
  padding: 15px;
  border-radius: 8px;
  border: 1px solid #d0d0d0;
  box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

/* Category containers */
.blue-container {
  background-color: #e0e8f0;
  border-left: 5px solid #0066cc;
  border: 1px solid #99bbdd;
}

.green-container {
  background-color: #e0f0e0;
  border-left: 5px solid #007700;
  border: 1px solid #99cc99;
}

.orange-container {
  background-color: #f0e8d0;
  border-left: 5px solid #cc7700;
  border: 1px solid #cc9933;
}

.purple-container {
  background-color: #e8e0f0;
  border-left: 5px solid #6600cc;
  border: 1px solid #bb99dd;
}

.red-container {
  background-color: #f0d0d0;
  border-left: 5px solid #cc0000;
  border: 1px solid #cc9999;
}

.gray-container {
  background-color: #e0e0e0;
  border-left: 5px solid #222;
  border: 1px solid #999;
}
```

### 1.5.2. Table Styling

```css
/* High-contrast table */
.high-contrast-table {
  width: 100%;
  border-collapse: collapse;
  border: 2px solid #999;
}

.high-contrast-table th {
  background-color: #d9d9d9;
  color: #111;
  font-weight: bold;
  text-align: left;
  padding: 10px;
  border: 1px solid #999;
}

.high-contrast-table td {
  padding: 10px;
  border: 1px solid #999;
  color: #444;
}

/* Category row styling */
.blue-row td:first-child {
  background-color: #e0e8f0;
  
  font-weight: bold;
}

.green-row td:first-child {
  background-color: #e0f0e0;
  color: #007700;
  font-weight: bold;
}
```

## 1.6. Mermaid Diagram Contrast

For Mermaid diagrams, use the following theme configuration:

```mermaid
%%{init: {'theme': 'base', 'themeVariables': {
  'primaryColor': '#0066cc',
  'primaryTextColor': '#fff',
  'primaryBorderColor': '#004080',
  'lineColor': '#004080',
  'secondaryColor': '#007700',
  'secondaryTextColor': '#fff',
  'secondaryBorderColor': '#005500',
  'tertiaryColor': '#cc7700',
  'tertiaryTextColor': '#fff',
  'tertiaryBorderColor': '#aa5500',
  'fontFamily': 'Arial, sans-serif',
  'fontSize': '16px',
  'taskTextColor': '#000',
  'taskTextDarkColor': '#fff',
  'taskTextOutsideColor': '#222',
  'sectionBkgColor': '#e0e0e0',
  'sectionBkgColor2': '#f0f0f0',
  'altSectionBkgColor': '#e0e0e0',
  'todayLineColor': '#cc0000'
}}}%%
```

## 1.7. Custom Instructions for Augment

Add the following to your Augment custom instructions to maintain high contrast in documentation:

```
# Documentation Accessibility and Contrast Standards

## 1. Color Contrast Requirements

    1.1. Minimum Contrast Ratios
         - Maintain 4.5:1 contrast ratio for normal text (WCAG AA)
         - Maintain 3:1 contrast ratio for large text (18pt+)
         - Verify contrast with WebAIM Contrast Checker

    1.2. Text Color Standards
         - Primary text: #111 (not #333)
         - Secondary text: #444 (not #7f8c8d)
         - Headings: #222
         - Never use light gray (#aaa, #ccc) on light backgrounds
         - Use font-weight: 500 for secondary text

    1.3. Category Color Coding
         - Documentation Index: #0066cc (blue)
         - Cross-Document Navigation: #007700 (green)
         - Diagram Accessibility: #cc7700 (orange)
         - Date and Version Formatting: #6600cc (purple)
         - Implementation Planning: #222 (dark gray)
         - Success indicators: #007700 (green)
         - Warning indicators: #cc7700 (orange)
         - Error indicators: #cc0000 (red)

    1.4. Background Colors
         - Main background: #f0f0f0
         - Container backgrounds: #fff with border
         - Category backgrounds: #e0e8f0 (blue), #e0f0e0 (green), #f0e8d0 (orange), #e8e0f0 (purple)
         - Table headers: #d9d9d9
         - Add 1px solid border to all containers (#d0d0d0 or category-specific)
```

## 1.8. Related Documents

- [../000-index.md](../000-index.md) - Main documentation index
- [./000-index.md](000-index.md) - Documentation standards index
- [../220-ela-documentation-style-guide-consolidated.md](../220-ela-documentation-style-guide-consolidated.md) - Documentation style guide
- [../230-documentation-roadmap.md](../230-documentation-roadmap.md) - Documentation roadmap

## 1.9. Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-21 | Initial version | Augment Agent |
