# Prompt Addenda Quick Reference

## 1. Overview

Prompt addenda are custom instructions that modify AI assistant behavior when working with your codebase. They're stored in `.augment/prompt-addenda.md` files at various directory levels.

## 2. Command Reference

| Command | Description |
|---------|-------------|
| `bin/augment-addenda help` | Show help information |
| `bin/augment-addenda show` | Show the primary addenda file |
| `bin/augment-addenda active` | Show consolidated active addenda |
| `bin/augment-addenda add` | Add new addenda (opens editor) |
| `bin/augment-addenda add 'text'` | Add specific text as addenda |
| `bin/augment-addenda clear` | Clear active addenda |
| `bin/augment-addenda list` | List all addenda files |

## 3. File Locations (In Priority Order)

1. Project-level: `[project-root]/.augment/prompt-addenda.md`
2. Parent directories: `[parent-dir]/.augment/prompt-addenda.md`
3. User-level: `[home-dir]/.augment/prompt-addenda.md`

## 4. File Structure

Only the "Active Addenda" section affects AI behavior:

```markdown
## Active Addenda

```
# Your instructions here
- Instruction 1
- Instruction 2
```
```

## 5. Best Practices

- Be specific and clear
- Group related instructions
- Include examples when helpful
- Use markdown formatting
- Update when project requirements change

## 6. Example Instructions

```markdown
# Coding Standards
- Follow PSR-12 for PHP files
- Use snake_case for variables and methods
- Add PHPDoc comments to all public methods

# Project Architecture
- Place new controllers in App\Http\Controllers namespace
- Use repository pattern for database access
- Implement service classes for business logic
```

For more detailed information, see the [Comprehensive Prompt Addenda Guide](005-prompt-addenda-guide.md).
