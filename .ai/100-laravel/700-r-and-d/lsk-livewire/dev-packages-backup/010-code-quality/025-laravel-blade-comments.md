# Laravel Blade Comments

## 1. Overview

Laravel Blade Comments is a package from Spatie that allows you to add comments to your Blade views without them being rendered in the HTML output. It's a convenient way to document your views while keeping the HTML output clean.

### 1.1. Package Information

- **Package Name**: spatie/laravel-blade-comments
- **Version**: ^1.3.1
- **GitHub**: [https://github.com/spatie/laravel-blade-comments](https://github.com/spatie/laravel-blade-comments)
- **Documentation**: [https://github.com/spatie/laravel-blade-comments#readme](https://github.com/spatie/laravel-blade-comments#readme)

## 2. Key Features

- Add comments to Blade templates
- Comments don't render in HTML output
- Support for multiline comments
- IDE-friendly syntax
- No impact on application performance
- Helpful for documenting templates

## 3. Usage Examples

### 3.1. Basic Comments

```html
{{-- This is a Blade comment that won't display in the HTML --}}
<div class="card">
    {{-- This inner comment also won't display --}}
    <div class="card-header">
        {{ $title }}
    </div>
</div>
```

### 3.2. Multiline Comments

```html
{{--
    This is a multiline comment.
    It can span multiple lines for detailed documentation.
    
    - Item 1
    - Item 2
    - Item 3
--}}
<form action="/submit" method="POST">
    @csrf
    <input type="text" name="email">
    <button type="submit">Submit</button>
</form>
```

## 4. Configuration

The package requires no special configuration beyond including it in your project:

```php
<?php

declare(strict_types=1);

// No special configuration needed
// The package works out of the box
```

## 5. Best Practices

### 5.1. Documenting Components

Use comments to document Blade components:

```html
{{-- 
    Alert Component
    --------------
    Display alerts with different styles.
    
    Props:
    - type: 'success', 'error', 'warning', 'info'
    - message: The message to display
    - dismissible: Whether the alert can be dismissed (default: false)
--}}
<x-alert
    type="success"
    :message="$message"
    :dismissible="true"
/>
```

### 5.2. Template Section Documentation

Document template sections:

```html
{{-- 
    MAIN NAVIGATION
    --------------
    This section contains the main navigation.
    It's used in the layout.blade.php template.
--}}
<nav class="main-nav">
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/about">About</a></li>
        <li><a href="/contact">Contact</a></li>
    </ul>
</nav>
```

### 5.3. Comments for Advanced Blade Features

Document more complex Blade features:

```html
{{--
    This loop uses $loop->iteration to create alternating row colors.
    The $loop variable provides metadata about the current loop.
--}}
@foreach ($users as $user)
    <tr class="{{ $loop->iteration % 2 === 0 ? 'bg-gray-100' : '' }}">
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
    </tr>
@endforeach
```
