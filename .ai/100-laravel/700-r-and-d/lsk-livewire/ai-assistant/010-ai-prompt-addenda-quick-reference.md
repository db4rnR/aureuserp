# AI Prompt Addenda Quick Reference

## 1. Overview

AI Prompt Addenda (AIPA) is a system for customizing the behavior of AI assistants when working with your codebase. This quick reference provides essential information for using the system.

## 2. File Locations

AI Prompt Addenda files are searched for in the following locations, in order of priority:

1. Project directory: `.aipa/ai-prompt-addenda.md`
2. Parent directories: `../.aipa/ai-prompt-addenda.md`, `../../.aipa/ai-prompt-addenda.md`, etc.
3. User's home directory: `~/.aipa/ai-prompt-addenda.md`

## 3. Command Reference

| Command | Description |
|---------|-------------|
| `bin/aipa help` | Show help information |
| `bin/aipa show` | Show the primary addenda file |
| `bin/aipa active` | Show consolidated active addenda |
| `bin/aipa add` | Add new addenda (opens editor) |
| `bin/aipa add 'text'` | Add specific text as addenda |
| `bin/aipa clear` | Clear active addenda |
| `bin/aipa list` | List all addenda files |
| `bin/aipa validate` | Validate addenda files for issues |
| `bin/aipa docs` | Generate documentation |
| `bin/aipa conditions` | Show detected project conditions |

## 4. Interactive CLI

For a more user-friendly interface, use the interactive CLI:

```bash
bin/aipa-cli
```

This provides a menu-driven interface for managing addenda files.

## 5. Advanced Features

- **Versioned sections**: `# Section Name (v2.0)` - Higher versions replace lower ones
- **Conditional sections**: `# [Laravel] Section Name` - Only applied when condition matches
- **Priority overrides**: `# Section Name (priority: high)` - Override default priority
- **File includes**: Add a `## Includes` section with paths to other addenda files

## 6. Best Practices

- Be specific and clear
- Group related instructions
- Include examples when helpful
- Use markdown formatting
- Update when project requirements change

## 7. Example Instructions

```markdown
# Coding Standards
- Follow PSR-12 for all PHP files
- Use snake_case for variable and method names
- Use PascalCase for class names
- Add type hints to all method parameters and return values
- Use strict typing with `declare(strict_types=1);`
- Maximum line length: 100 characters

# Communication Preferences
- Be concise and direct in explanations
- Provide code examples for complex concepts
- Explain the reasoning behind architectural suggestions
- When suggesting multiple approaches, indicate the recommended one
- Include links to relevant documentation when available
```

## 8. Web Interface

Access the web interface at:

```
http://your-project-url/aipa
```

Features include:
- Visual editing with syntax highlighting
- Real-time validation
- Live preview
- Template system

For more information, see the [AI Prompt Addenda Guide](005-ai-prompt-addenda-guide.md).
