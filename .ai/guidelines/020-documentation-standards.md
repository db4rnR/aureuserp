# 2. Documentation Standards

## 2.1. Structure and Organization

### 2.1.1. Hierarchical Numbering

- Number all headings sequentially (1, 1.1, 1.1.1, etc.)
- Exclude main document title from numbering
- Precede and succeed all headings with blank lines
- Apply consistently across all documentation types
- All markdown files, except where the basename is UPPERCASE, must have a TOC
- Where a document is split into multiple parts:
  - The same, complete TOC should be included in each part, with an indication of which part is "current"
  - Heading numbering should be consistent, contiguous and continuous across all parts of the document

### 2.1.2. Multi-Document Projects

When multiple documents are required:

- Create `000-index.md` within each folder
  - The sequence of entries should be logically consistent
- Use consistent 3-digit prefix numbering system for all documentation files
- Ensure consistency with the sequence of entries in `000-index.md`
- Exception: files with uppercase basenames OR in folders with non-hyphenated names

**Standard Documentation File Naming:**

- **3-digit multiples of 10**
- **Starting at 010-**
- **Incrementing by 10** (010, 020, 030, 040, 050, etc.)
- **Prefix unique amongst sibling files/folders**, **EXCEPT**:
  - Multi-part documents where the same 3-digit prefix is required and a second 3-digit prefix is appended to the first
  - The second prefix follows the same rules as the first:
    - 3-digit multiples of 10
    - Starting at 010-
    - Incrementing by 10 (010, 020, 030, 040, 050, etc.)

**Examples:**

- Single documents: `010-introduction.md`, `020-setup.md`, `030-configuration.md`
- Multi-part documents: `010-010-part-one.md`, `010-020-part-two.md`, `010-030-part-three.md`

### 2.1.3. File and Folder Naming

- Use kebab-case for file names
- Exclude special characters
- Use descriptive names with file extensions
- Maintain consistent naming conventions
- Follow standard documentation file naming (section 2.1.2) for numbered documents

### 2.1.4. Exercise Organization

- Include exercise sections with questions and practical exercises
- Organize exercises in dedicated `888-exercises` folder
- Organize answers in `888-sample-answers` folder
- Ensure consistency between exercise files and sample answer files

## 2.2. Content Formatting

### 2.2.1. Code Blocks

- Format with explicit language specifications
- Use proper code fence syntax (e.g., `~~~python`, `~~~javascript`, `~~~html`)
- Enclose HTML snippets in code fences with 'html' specified

### 2.2.2. Markdown Links

- Use proper markdown syntax: `[link text](https://example.com)`
- Ensure all links are valid and accessible
- Avoid light gray colors on light backgrounds

### 2.2.3. Markdown Lists

- Avoid 4+ space indentation (prevents code block rendering that disables links)
- Use standard indentation (dash/asterisk + single space, or two spaces for nested)
- Surround lists with blank lines (MD032 compliance)
- Add spacing between list items (margin-bottom: 5px)

## 2.3. Visual Design and Accessibility

### 2.3.1. Color Contrast Requirements

- Maintain 4.5:1 contrast ratio for normal text (WCAG AA)
- Maintain 3:1 contrast ratio for large text (18pt+)
- Verify with WebAIM Contrast Checker

**Contrast Validation Protocol:**

- **Before publication:** Check all text/background combinations
- **Systematic review:** Validate colored containers, code blocks, tables, and highlighted text
- **Tools:** Use browser developer tools or online contrast checkers
- **Documentation:** Record contrast ratios for reusable color schemes

**Common Contrast Violations to Avoid:**

- Light gray text (`#aaa`, `#ccc`, `#999`) on light backgrounds
- Default syntax highlighting in colored containers
- Insufficient contrast in tables and emphasized text
- Poor contrast in interactive elements (buttons, links)

### 2.3.2. Text Color Standards

- Primary text: `#111` (not `#333`)
- Secondary text: `#444` (not `#7f8c8d`)
- Headings: `#222`
- Never use light gray (`#aaa`, `#ccc`) on light backgrounds

### 2.3.3. Category Color Coding

- Documentation Index: `#0066cc` (blue)
- Cross-Document Navigation: `#007700` (green)
- Diagram Accessibility: `#cc7700` (orange)
- Date and Version Formatting: `#6600cc` (purple)
- Implementation Planning: `#222` (dark gray)
- Success indicators: `#007700` (green)
- Warning indicators: `#cc7700` (orange)
- Error indicators: `#cc0000` (red)

### 2.3.4. Background Colors

- Main background: `#f0f0f0`
- Container backgrounds: `#fff` with border
- Category backgrounds: `#e0e8f0` (blue), `#e0f0e0` (green), `#f0e8d0` (orange), `#e8e0f0` (purple)
- Table headers: `#d9d9d9`
- Add 1px solid border to all containers (`#d0d0d0` or category-specific)

### 2.3.5. Typography Standards

**Font Weights:**

- Bold (`font-weight: bold`) for headings and important text
- Medium weight (`font-weight: 500`) for secondary text
- Avoid normal weight for small text

**Text Sizing:**

- Minimum body text: 14px
- Minimum secondary text: 12px

### 2.3.6. Text Enhancement

- Use background highlighting for emphasis in lists
- Add padding around emphasized text (3px 6px)
- Use borders to define boundaries

### 2.3.7. Code Block Contrast Standards

**Critical Accessibility Issue:** Code blocks within colored containers inherit parent text colors, creating severe accessibility violations.

**Problem Identification:**

- Default syntax highlighting uses light colors that fail contrast requirements on colored backgrounds
- PHP code blocks in colored divs become unreadable for users with visual impairments
- Standard markdown code blocks don't override parent container text colors

**Required Solution - Dark Code Block Containers:**

**Mandatory Contrast Standards:**

- **Code Block Background:** `#1e1e1e` (VS Code dark theme)
- **Code Block Text:** `#d4d4d4` (light gray)
- **Contrast Ratio:** 21:1 (exceeds WCAG AAA requirements)
- **Font Family:** Monospace (`'Fira Code', 'Consolas', 'Monaco', monospace`)

**Implementation Requirements:**

- **Always wrap code blocks** in dark containers when placed within colored divs
- **Never rely** on inherited text colors for code blocks
- **Test all code blocks** for contrast compliance
- **Use consistent styling** across all code examples

## 2.4. Visual Learning Aids

### 2.4.1. Mermaid Diagram Standards

- Create extensive explanations with colorful Mermaid diagrams
- Use high contrast for legibility
- Include visual learning aids with consistent diagram styles

**Mermaid Theme Configuration:**

- Use high-contrast theme variables
- Set `primaryColor: '#0066cc'`
- Set `fontFamily: 'Arial, sans-serif'`
- Set `fontSize: '16px'`
- Use white text on colored backgrounds
- Add borders to all elements

### 2.4.2. Mode Support

- Support both dark/light mode selection in documentation
- Organize documentation with complete structure and no empty folders
- Use PHP attributes rather than PHPDocs meta tags in documentation

### 2.4.3. Accessibility Testing Workflow

**Pre-Publication Checklist:**

1. **Contrast validation:** Verify all text/background combinations meet WCAG AA standards
2. **Code block review:** Ensure all code blocks have proper dark container wrapping
3. **Visual hierarchy test:** Check heading structure and color coding consistency
4. **Responsive testing:** Verify readability at various zoom levels (200-400%)
5. **Print compatibility:** Ensure dark code blocks remain legible when printed

## 2.5. Technical Accuracy

### 2.5.1. Command Verification

- Verify all commands against official documentation before inclusion
- Include direct links to official documentation for package-related commands
- Test commands or verify from official sources before documenting
- Prioritize technical accuracy over content reorganization
- Include troubleshooting sections for common command errors
- Document exact source of each command with references

### 2.5.2. Package Configuration

- Double-check tag names for publishing configurations and migrations
- Verify exact tag names required for publishing when documenting package configuration

### 2.5.3. Asset Management

- All assets used in project documentation should be stored in suitably-named folders/files within `docs/assets/` directory of the project root.

## 2.6. Content Validation

- Validate all content adheres to formatting rules
- Check for consistent numbering
- Verify code block specifications
- Test all markdown links
- Ensure technical accuracy throughout
- **Accessibility verification:** Apply systematic contrast testing workflow (section 2.4.3)
- **Code block compliance:** Ensure all code blocks in colored containers use dark wrapping (section 2.3.7)

## 2.7. Markdown Formatting Rules

### 2.7.1. Headings

- Use ATX style headings (use `#`)
- Increment heading levels by one at a time (MD001)
- Add single space after hash (MD018)
- Surround headings with blank lines (MD022)
- Start headings at beginning of line (MD023)
- Allow multiple headings with same content in different sections (MD024)
- Avoid trailing punctuation except `.`, `,`, `;`, `:`, `!` (MD026)
- First line should be top-level heading (MD041)

### 2.7.2. Lists

- Use dash (`-`) for unordered lists (MD004)
- Consistent indentation, 4 spaces for unordered lists (MD007)
- Use `ordered` prefixes for ordered lists (1., 2., 3.) (MD029)
- One space after list markers (MD030)
- Surround lists with blank lines (MD032)

### 2.7.3. Code Blocks

- Surround fenced code blocks with blank lines (MD031)
- Use fenced code blocks style (MD046)
- Use three backticks for code fence style (MD048)
- Specify language for fenced code blocks (MD040)

### 2.7.4. Links and Images

- Use correct link syntax `[link text](url)` (avoid MD011)
- Prefer proper markdown link syntax over bare URLs
- Avoid spaces inside link text (MD039)
- No empty links `[]()` (MD042)
- Allowed URI schemes: `http`, `https`, `ftp`, `mailto`, `tel`, `file`, `data`, `/`
- Images should have alternate text (MD045)
- Link fragments should be valid (MD051)
- Reference links must exist and be used (MD052, MD053)

### 2.7.5. Emphasis and Style

- Avoid spaces inside emphasis markers (MD037)
- Use asterisks (`*`) for emphasis/italics (MD049)
- Use double asterisks (`**`) for strong/bold (MD050)

### 2.7.6. Spacing and General Formatting

- Allow trailing spaces for line breaks (2 spaces = `<br>`) (MD009)
- No hard tabs, use spaces (MD010)
- Maximum one consecutive blank line (MD012)
- Avoid spaces inside inline code spans (MD038)
- Use `---` for horizontal rules (MD035)
- Files end with single newline (MD047)

### 2.7.7. HTML and Tables

- Specific HTML elements allowed: `div`, `span`, `a`, `img`, `strong`, `em`, `br`, `hr`, `table`, `thead`, `tbody`, `tr`, `th`, `td`, `details`, `summary`, `sup`, `sub`, `kbd`, `h1-h6`, `ul`, `ol`, `li`, `p`, `blockquote`, `pre`, `code`
- Add spaces around pipes for readability in tables (MD055)
- Consistent column count across rows in tables (MD056)

### 2.7.8. Blockquotes

- Avoid multiple spaces after blockquote symbol (`>`) (MD027)
- Blank lines inside blockquotes are allowed (MD028)
