# Markdown Linting Configuration

This document explains the MarkdownLint configuration used in this project to ensure consistent Markdown formatting and support for repository-root-relative links.

## 1. Configuration Files

The project includes the following configuration files:

- `.markdownlint.json` - Core rules configuration
- `.markdownlint-cli2.jsonc` - CLI-specific configuration
- `.vscode/settings.json` - VS Code integration settings

## 2. Key Features

### 2.1. Repository-Root-Relative Links

The configuration supports GitHub-style repository-root-relative links (starting with `/`):

```markdown
[Link to file](/path/from/repo/root/to/file.md)
```

These links are properly validated and work both in GitHub and in your IDE.

### 2.2. High Contrast Support

The configuration enforces high-contrast formatting for better accessibility:

- Proper heading structure
- Consistent list formatting
- Required alt text for images
- Proper spacing around elements

### 2.3. Consistent Styling

The configuration enforces:

- ATX-style headings (`#` instead of `===`)
- Dash-style unordered lists (`-` instead of `*`)
- Consistent emphasis markers (`*` for italic, `**` for bold)
- Fenced code blocks with language specification

## 3. Rule Customizations

### 3.1. Disabled Rules

The following rules are disabled to accommodate project requirements:

- `MD013` (Line length) - Disabled to allow for flexibility in documentation
- `MD034` (Bare URLs) - Disabled to allow for flexibility
- `MD036` (Emphasis used instead of heading) - Disabled for flexibility
- `MD043` (Required heading structure) - Disabled for flexibility
- `MD044` (Proper names capitalization) - Disabled for flexibility

### 3.2. Modified Rules

The following rules are modified:

- `MD007` (Unordered list indentation) - Set to 4 spaces
- `MD012` (Multiple consecutive blank lines) - Limited to 1
- `MD024` (Multiple headings with same content) - Allowed in different sections
- `MD033` (HTML in Markdown) - Allowed specific HTML tags for accessibility and styling

## 4. IDE Integration

### 4.1. VS Code

The VS Code settings include:

- Path mapping for repository-root-relative links
- Markdown validation settings
- Editor settings optimized for Markdown
- Color customization for high contrast

### 4.2. Required Extensions

For the best experience, install these VS Code extensions:

- [markdownlint](https:/marketplace.visualstudio.com/items?itemName=DavidAnson.vscode-markdownlint)
- [Markdown All in One](https:/marketplace.visualstudio.com/items?itemName=yzhang.markdown-all-in-one)
- [Path Intellisense](https:/marketplace.visualstudio.com/items?itemName=christian-kohler.path-intellisense)

## 5. Using with PhpStorm

For PhpStorm users:

1. Install the [Markdown Navigator Enhanced](https:/plugins.jetbrains.com/plugin/7896-markdown-navigator-enhanced) plugin
2. Configure the plugin to use the project's `.markdownlint.json` file
3. Mark the repository root as a Sources Root
4. Configure path mappings if needed

## 6. Command Line Usage

To lint Markdown files from the command line:

```bash
# Install markdownlint-cli2
npm install -g markdownlint-cli2

# Run linting
markdownlint-cli2 "**/*.md"

# Fix auto-fixable issues
markdownlint-cli2-fix "**/*.md"
```

## 7. Customizing the Configuration

If you need to customize the configuration:

1. Edit `.markdownlint.json` for rule changes
2. Edit `.markdownlint-cli2.jsonc` for CLI-specific changes
3. Edit `.vscode/settings.json` for IDE-specific changes

Remember to maintain support for repository-root-relative links when making changes.
