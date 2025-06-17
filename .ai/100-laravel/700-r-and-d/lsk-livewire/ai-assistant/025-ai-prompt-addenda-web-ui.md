# AI Prompt Addenda Web UI

## 1. Overview

The AI Prompt Addenda Web UI provides a user-friendly interface for managing prompt addenda files. It offers visual editing with syntax highlighting, real-time validation, and preview capabilities.

## 2. Features

- **Visual Editing**: Edit addenda files with a full-featured code editor
- **Syntax Highlighting**: Markdown syntax highlighting for better readability
- **Real-time Validation**: Instant feedback on validation issues
- **Live Preview**: See how your active addenda will be rendered
- **Template System**: Choose from pre-built templates for common use cases
- **Documentation Generation**: Generate comprehensive documentation with a single click
- **Project Conditions**: View detected project conditions for conditional sections
- **Modern UI**: Built with FluxUI components for a polished experience

## 3. Getting Started

### 3.1. Accessing the Web UI

The Web UI is available at:

```
http://your-project-url/aipa
```

### 3.2. Navigation

The Web UI provides the following pages:

- **Home**: Lists all addenda files and shows consolidated active addenda
- **Editor**: Unified interface for creating and editing addenda files
- **Docs**: Generate and view documentation for active addenda

## 4. Using the Web UI

### 4.1. Viewing Addenda Files

The home page displays all addenda files in priority order (highest first). It also shows the consolidated active addenda after processing and provides information about detected project conditions.

### 4.2. Creating and Editing Addenda

The Editor page provides a unified interface for both creating and editing addenda files:

1. To create a new file, click "Create New Addenda" on the home page
   - Enter the file path (relative to project root)
   - Choose a template or start from scratch
   - Edit the content in the code editor
   - Check the validation results and preview
   - Click "Create File" to save

2. To edit an existing file, click "Edit" next to the file on the home page
   - Modify the content in the code editor
   - Check the validation results and preview
   - Click "Save Changes" to update the file

### 4.3. Generating Documentation

To generate documentation:

1. Click "Generate Documentation" on the home page
2. The system will create a markdown file documenting all active addenda
3. The documentation will be displayed for review

## 5. Code Editor Features

The Web UI uses the Monaco Editor (the same editor used in VS Code) with the following features:

- Syntax highlighting for Markdown
- Line numbers
- Minimap navigation
- Word wrap
- Auto-indentation
- Search and replace
- Keyboard shortcuts

## 6. Templates

The Web UI provides several templates for common use cases:

- **Empty**: Basic structure with empty active addenda
- **Coding Standards**: Common coding standards and conventions
- **Architecture**: Project architecture guidelines
- **Communication**: Communication style preferences
- **Laravel**: Laravel-specific development guidelines
- **Testing**: Testing standards and practices

## 7. Integration with CLI Tools

The Web UI complements the existing command-line tools. Changes made through the Web UI are immediately available to the CLI tools and vice versa.

## 8. Technical Details

The Web UI is built using:

- Laravel framework
- Laravel Folio for routing
- FluxUI components for the user interface
- Monaco Editor for code editing
- Tailwind CSS for styling
- Marked.js for Markdown rendering

## 9. Troubleshooting

### 9.1. Validation Issues

If you encounter validation issues:

- Check that your file has the required sections (main title, Active Addenda section)
- Ensure the Active Addenda section has a code block (```...```)
- Verify that section headers follow the correct format

### 9.2. File Permissions

If you cannot save files:

- Check that the web server has write permissions to the directory
- Verify that the directory exists (it will be created automatically if possible)

## 10. Conclusion

The AI Prompt Addenda Web UI provides a user-friendly alternative to the command-line tools for managing prompt addenda. It offers visual editing, real-time validation, and preview capabilities to make it easier to create and maintain high-quality addenda files. The unified editor interface simplifies the workflow, while the FluxUI components provide a modern, responsive design.
