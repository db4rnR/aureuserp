# Laravel Blade Comments

## 1. Overview

Laravel Blade Comments is a package by Spatie that allows you to add comments to your Blade templates that are visible in the source code but not in the rendered HTML. This is useful for documenting your Blade templates without affecting the output.

### 1.1. Package Information

- **Package Name**: spatie/laravel-blade-comments
- **Version**: ^1.3.1
- **GitHub**: [https://github.com/spatie/laravel-blade-comments](https://github.com/spatie/laravel-blade-comments)
- **Documentation**: [https://github.com/spatie/laravel-blade-comments#readme](https://github.com/spatie/laravel-blade-comments#readme)

## 2. Key Features

- Add comments to Blade templates
- Comments are visible in source code but not in rendered HTML
- Support for multiline comments
- Simple and lightweight
- No configuration required
- Works with Laravel 12 and PHP 8.4

## 3. Installation

```bash
composer require --dev spatie/laravel-blade-comments
```

## 4. Usage

### 4.1. Basic Usage

Add comments to your Blade templates using the `{{-- --}}` syntax:

```blade
{{-- This is a regular Blade comment --}}
<div>
    <h1>{{ $title }}</h1>
    {{-- This comment won't be visible in the rendered HTML --}}
    <p>{{ $content }}</p>
</div>
```

### 4.2. Multiline Comments

You can use multiline comments:

```blade
{{--
    This is a multiline comment
    that spans multiple lines
    and won't be visible in the rendered HTML
--}}
<div>
    <h1>{{ $title }}</h1>
    <p>{{ $content }}</p>
</div>
```

### 4.3. Documenting Components

Use comments to document Blade components:

```blade
{{-- 
    Alert Component
    --------------
    Props:
    - type: string (info, success, warning, error)
    - message: string
    - dismissible: boolean
--}}
<x-alert type="info" :message="$message" :dismissible="true" />
```

### 4.4. Documenting Sections

Use comments to document Blade sections:

```blade
{{-- 
    Main Content Section
    -------------------
    This section contains the main content of the page.
    It should be overridden by child templates.
--}}
@section('content')
    <p>Default content</p>
@endsection
```

## 5. Best Practices

### 5.1. Documentation Comments

Use comments to document:

1. **Component Props**: Document the props that a component accepts
2. **Sections**: Document the purpose of each section
3. **Complex Logic**: Explain complex Blade logic
4. **TODO Items**: Mark areas that need attention
5. **Author Information**: Add author information for complex templates

### 5.2. Comment Style

Follow a consistent comment style:

```blade
{{-- 
    Component Name
    -------------
    Brief description of the component.
    
    Props:
    - prop1: type (description)
    - prop2: type (description)
    
    Example:
    <x-component prop1="value" :prop2="$variable" />
--}}
```

### 5.3. Comment Placement

Place comments strategically:

1. At the top of files for file-level documentation
2. Before components to document their usage
3. Before complex logic to explain what's happening
4. Inline for brief explanations

## 6. Integration with Laravel 12 and PHP 8.4

Laravel Blade Comments is fully compatible with Laravel 12 and PHP 8.4. It works seamlessly with:

- Blade components
- Livewire components
- Volt components
- Laravel's new Folio pages

## 7. Advanced Usage

### 7.1. IDE Integration

Most IDEs recognize Blade comments and provide syntax highlighting. Some IDEs also support:

- Collapsing comments
- Comment folding
- Comment navigation

### 7.2. Documentation Generation

You can parse Blade comments to generate documentation:

1. Create a custom Artisan command to scan Blade files
2. Extract comments using regular expressions
3. Generate Markdown documentation

## 8. Alternatives

If you need more advanced documentation features, consider:

1. **PHPDoc for PHP Classes**: Use PHPDoc for PHP classes and methods
2. **Blade Directives**: Create custom Blade directives for documentation
3. **External Documentation**: Maintain separate documentation files

## 9. Best Practices for Team Usage

When using Laravel Blade Comments in a team:

1. **Establish Conventions**: Agree on comment format and style
2. **Document Standards**: Create documentation standards
3. **Review Comments**: Include comment quality in code reviews
4. **Update Comments**: Keep comments up-to-date with code changes
