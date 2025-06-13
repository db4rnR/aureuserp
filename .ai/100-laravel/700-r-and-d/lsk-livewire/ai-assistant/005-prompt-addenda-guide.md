# Prompt Addenda Guide

## 1. Introduction

### 1.1. What Are Prompt Addenda?

Prompt addenda are custom instructions that extend or modify the standard behavior of AI assistants when they interact with your codebase. These instructions are stored in files that can be placed at various levels of your directory structure, allowing for both project-specific and global customizations.

### 1.2. Benefits of Using Prompt Addenda

- **Customized Assistance**: Tailor AI behavior to your specific project needs
- **Consistent Practices**: Ensure AI follows your team's coding standards and practices
- **Hierarchical Control**: Apply different instructions at different levels (project, parent directory, user-wide)
- **Improved Productivity**: Reduce repetitive instructions and corrections

### 1.3. How Prompt Addenda Work

The system searches for `.augment/prompt-addenda.md` files in multiple locations:
- Starting from your current project root
- Moving up through parent directories
- Checking your user home directory

Files are prioritized by proximity to the project root, with the closest having the highest priority. When the AI assistant processes your requests, it incorporates these addenda into its behavior, with higher priority instructions taking precedence when conflicts arise.

## 2. Installation and Setup

### 2.1. Prerequisites

- PHP 8.2 or higher
- Access to create directories and files in your project

### 2.2. Directory Structure

Prompt addenda are stored in a specific structure:
```
[directory]/
└── .augment/
    └── prompt-addenda.md
```

Where `[directory]` can be:
- Your project root
- Any parent directory of your project
- Your user home directory

### 2.3. Creating Your First Addenda File

The easiest way to create your first addenda file is to use the provided command-line tool:

```bash
bin/augment-addenda add
```

This will:
1. Create a `.augment` directory in your current project if it doesn't exist
2. Create a template `prompt-addenda.md` file if it doesn't exist
3. Open your default text editor to add your custom instructions

## 3. Command-Line Tool Reference

### 3.1. Overview

The `bin/augment-addenda` command-line tool provides a convenient way to manage your prompt addenda files.

### 3.2. Available Commands

#### 3.2.1. Help

```bash
bin/augment-addenda help
```

Displays help information about the available commands.

#### 3.2.2. Show

```bash
bin/augment-addenda show
```

Shows the content of the primary (highest priority) addenda file.

#### 3.2.3. Active

```bash
bin/augment-addenda active
```

Shows the consolidated active addenda from all files, in priority order.

#### 3.2.4. Add

```bash
bin/augment-addenda add
```

Opens your default text editor to add new addenda to the primary file.

```bash
bin/augment-addenda add 'Your custom instructions here'
```

Adds the specified text as addenda to the primary file without opening an editor.

#### 3.2.5. Clear

```bash
bin/augment-addenda clear
```

Clears the active addenda section from the primary file.

#### 3.2.6. List

```bash
bin/augment-addenda list
```

Lists all addenda files found in the system, in priority order (highest first).

### 3.3. Priority Rules

Files are prioritized as follows:
1. Files closer to the project root have higher priority
2. Files in parent directories have lower priority
3. Files in the user's home directory have lowest priority

## 4. Writing Effective Addenda

### 4.1. File Structure

Each `prompt-addenda.md` file has the following structure:

```markdown
# Augment AI Assistant Prompt Addenda

This file contains additional instructions that extend the standard prompt for the Augment AI Assistant.

## Custom Instructions

Add your custom instructions below. These will be appended to the standard prompt and will guide the assistant's behavior.

```
# Your custom instructions here
```

## Usage Guidelines

1. Keep instructions clear and concise
2. Use markdown formatting for better readability
3. Group related instructions together
4. Use examples when helpful
5. Update this file whenever you need to modify the assistant's behavior

## Example Addenda

```
# Project-Specific Conventions
- Always use snake_case for variable names in PHP files
- Follow the repository's existing code style for new code
- When creating new classes, place them in the appropriate namespace based on their functionality
- Add PHPDoc comments to all public methods

# Communication Preferences
- Be concise in explanations
- Provide code examples when explaining concepts
- When suggesting multiple approaches, clearly indicate the recommended one
- Always explain the reasoning behind architectural decisions
```

## Active Addenda

The following custom instructions are currently active:

```
# Add your active custom instructions here
```
```

### 4.2. Active Addenda Section

The "Active Addenda" section is the only part that affects the AI assistant's behavior. The rest of the file serves as documentation and examples.

### 4.3. Best Practices

#### 4.3.1. Be Specific and Clear

```markdown
# Good Example
- Use PSR-12 coding standards for all PHP files
- Place new controllers in the App\Http\Controllers namespace
- Follow the repository pattern for database interactions

# Less Effective Example
- Write good code
- Use proper namespaces
- Follow best practices
```

#### 4.3.2. Group Related Instructions

Organize instructions by category to make them easier to understand and maintain:

```markdown
# Coding Standards
- Use PSR-12 for PHP files
- Use 2-space indentation for JavaScript
- Maximum line length: 100 characters

# Documentation
- Add PHPDoc comments to all public methods
- Include parameter and return type documentation
- Document exceptions that may be thrown
```

#### 4.3.3. Include Examples When Helpful

```markdown
# Naming Conventions
- Use snake_case for variables and methods
  Example: `$user_count`, `calculate_total()`
- Use PascalCase for class names
  Example: `UserRepository`, `PaymentProcessor`
- Use SCREAMING_SNAKE_CASE for constants
  Example: `MAX_ATTEMPTS`, `DEFAULT_TIMEOUT`
```

#### 4.3.4. Specify Project-Specific Patterns

```markdown
# Project Architecture
- Follow the CQRS pattern for all new features
- Use Form Request classes for validation
- Implement service classes in App\Services namespace
- Use repositories for database access
```

## 5. Advanced Usage

### 5.1. Multi-Level Configuration

You can create addenda files at different levels to apply different types of instructions:

#### 5.1.1. Project-Level (Highest Priority)

`.augment/prompt-addenda.md` in your project root:
```markdown
# Project-Specific Instructions
- Follow the project's coding standards in /docs/standards.md
- Use the existing architecture patterns
- Add tests for all new features
```

#### 5.1.2. Organization-Level (Medium Priority)

`.augment/prompt-addenda.md` in a parent directory containing multiple projects:
```markdown
# Organization Standards
- Include copyright headers in all new files
- Follow the company security guidelines
- Use approved third-party libraries only
```

#### 5.1.3. User-Level (Lowest Priority)

`.augment/prompt-addenda.md` in your home directory:
```markdown
# Personal Preferences
- Provide detailed explanations for complex code
- Include links to relevant documentation
- Suggest alternative approaches when appropriate
```

### 5.2. Temporary Overrides

For temporary changes to AI behavior, you can modify the active addenda and then clear them when done:

```bash
# Add temporary instructions
bin/augment-addenda add 'Focus on performance optimization for this session'

# When finished, clear the temporary instructions
bin/augment-addenda clear
```

### 5.3. Team Collaboration

For team projects, consider:

1. Adding the project-level `.augment/prompt-addenda.md` to version control
2. Documenting the available addenda commands in your project README
3. Establishing team guidelines for modifying shared addenda

## 6. Troubleshooting

### 6.1. Common Issues

#### 6.1.1. Addenda Not Being Applied

**Symptoms**: The AI assistant isn't following your custom instructions.

**Possible Solutions**:
- Check that your addenda are in the "Active Addenda" section
- Verify the file is in the correct location with `bin/augment-addenda list`
- Ensure the format of your addenda file is correct
- Check for syntax errors in your instructions

#### 6.1.2. Conflicting Instructions

**Symptoms**: The AI assistant is following some instructions but ignoring others.

**Possible Solutions**:
- Check for conflicting instructions across different addenda files
- Remember that higher priority files (closer to project root) override lower priority ones
- Use `bin/augment-addenda active` to see the consolidated instructions

#### 6.1.3. Command Not Found

**Symptoms**: The `bin/augment-addenda` command returns "command not found".

**Possible Solutions**:
- Ensure the script is executable: `chmod +x bin/augment-addenda`
- Run the command from the project root directory
- Check that PHP is installed and in your PATH

### 6.2. Getting Help

If you encounter issues not covered in this guide:
- Check the implementation in `.augment/load-addenda.php`
- Examine the command-line tool in `bin/augment-addenda`
- Consult with your team's AI tools administrator

## 7. Examples

### 7.1. Coding Standards Addenda

```markdown
# Coding Standards
- Follow PSR-12 for all PHP files
- Use snake_case for variable and method names
- Use PascalCase for class names
- Add type hints to all method parameters and return values
- Use strict typing with `declare(strict_types=1);`
- Maximum line length: 100 characters
- Use single quotes for strings unless double quotes are needed
- Add PHPDoc comments to all public methods
```

### 7.2. Project Architecture Addenda

```markdown
# Architecture Guidelines
- Place controllers in App\Http\Controllers namespace
- Place models in App\Models namespace
- Use repository pattern for database access
- Implement service classes for business logic
- Use form request classes for validation
- Follow SOLID principles
- Implement interfaces for all services
- Use dependency injection instead of static methods
```

### 7.3. Communication Preferences Addenda

```markdown
# Communication Style
- Be concise and direct in explanations
- Provide code examples for complex concepts
- Explain the reasoning behind architectural suggestions
- When suggesting multiple approaches, indicate the recommended one
- Include links to relevant documentation when available
- Use bullet points for lists of options or steps
```

## 8. Conclusion

Prompt addenda provide a powerful way to customize AI assistant behavior to match your project requirements and personal preferences. By creating a hierarchy of addenda files, you can apply different levels of customization from project-specific details to organization-wide standards and personal preferences.

The command-line tool makes it easy to manage your addenda, and the priority system ensures that the most relevant instructions take precedence. With well-crafted addenda, you can significantly enhance the effectiveness of AI assistance in your development workflow.
