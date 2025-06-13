# Symfony Polyfill PHP 8.4

## 1. Overview

Symfony Polyfill PHP 8.4 is a package that provides PHP 8.4 features to lower PHP versions. It allows you to use PHP 8.4 functionality in projects running on PHP 8.0, 8.1, 8.2, or 8.3, ensuring compatibility while taking advantage of the latest language features.

### 1.1. Package Information

- **Package Name**: symfony/polyfill-php84
- **Version**: ^1.29
- **GitHub**: [https://github.com/symfony/polyfill-php84](https://github.com/symfony/polyfill-php84)
- **Documentation**: [https://github.com/symfony/polyfill-php84#readme](https://github.com/symfony/polyfill-php84#readme)

## 2. Key Features

- Provides PHP 8.4 functionality in lower PHP versions
- Zero configuration required
- Minimal performance impact
- Automatic feature detection
- Seamless integration
- Support for key PHP 8.4 features:
  - `json_validate` function
  - `str_increment` and `str_decrement` functions
  - `stream_context_set_options` function
  - `stream_context_get_options` function
  - `stream_context_set_option` function
  - `stream_context_get_option` function
  - `stream_context_get_params` function
  - `stream_context_set_params` function
  - `stream_context_get_default` function
  - `stream_context_set_default` function
  - `stream_context_create` function
  - `stream_context_get_default_options` function
  - `stream_context_set_default_options` function

## 3. Installation

```bash
composer require symfony/polyfill-php84
```

## 4. Usage

### 4.1. Basic Usage

Once installed, the polyfill automatically provides PHP 8.4 features. You can use them as if you were running PHP 8.4:

```php
// Use PHP 8.4 json_validate function in any PHP version
if (json_validate($jsonString)) {
    // Process valid JSON
}

// Use PHP 8.4 str_increment function
$nextString = str_increment('a'); // 'b'
$nextString = str_increment('z'); // 'aa'
$nextString = str_increment('A9'); // 'B0'

// Use PHP 8.4 str_decrement function
$prevString = str_decrement('b'); // 'a'
$prevString = str_decrement('aa'); // 'z'
$prevString = str_decrement('B0'); // 'A9'
```

### 4.2. Stream Context Functions

```php
// Create a stream context with options
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'User-Agent: PHP',
    ],
]);

// Get options from a stream context
$options = stream_context_get_options($context);

// Set options for a stream context
stream_context_set_options($context, [
    'http' => [
        'timeout' => 10,
    ],
]);

// Get a specific option from a stream context
$method = stream_context_get_option($context, 'http', 'method');

// Set a specific option in a stream context
stream_context_set_option($context, 'http', 'timeout', 20);
```

## 5. Integration with Laravel 12 and PHP 8.4

In a Laravel 12 project running on PHP 8.4, this polyfill is not strictly necessary since PHP 8.4 already provides these features natively. However, it's useful in several scenarios:

1. **Backward Compatibility**: If your project needs to support both PHP 8.4 and lower versions
2. **Dependency Management**: If your project depends on packages that require this polyfill
3. **Testing Environments**: If your testing environments might run on different PHP versions

Laravel 12 is designed to work with PHP 8.2 and higher, so this polyfill helps bridge the gap between PHP 8.2/8.3 and PHP 8.4 features.

## 6. Performance Considerations

The polyfill has minimal performance impact:

1. **Native Detection**: It automatically detects if the PHP version already supports a feature and uses the native implementation when available
2. **Optimized Implementation**: The polyfill implementations are optimized for performance
3. **Selective Loading**: Only the required polyfills are loaded

## 7. Best Practices

1. **Use as Dependency**: Let Composer manage the polyfill as a dependency rather than including it manually
2. **Version Constraints**: Use appropriate version constraints in your `composer.json`
3. **Feature Detection**: Use feature detection rather than version detection in your code
4. **Documentation**: Document the use of PHP 8.4 features in your codebase
5. **Testing**: Test your code on both PHP 8.4 and lower versions to ensure compatibility

## 8. Troubleshooting

### 8.1. Function Already Defined

If you encounter "function already defined" errors:

1. Ensure you're not manually including the polyfill
2. Check for conflicts with other polyfills or libraries
3. Update to the latest version of the polyfill

### 8.2. Unexpected Behavior

If you encounter unexpected behavior:

1. Check if the polyfill implementation has any known limitations
2. Verify that your code works correctly on native PHP 8.4
3. Report issues to the Symfony polyfill repository

## 9. Alternatives

If you need more comprehensive PHP 8.4 support:

1. **Upgrade to PHP 8.4**: The best option is to upgrade your PHP version
2. **Multiple Polyfills**: Use multiple polyfills for different PHP features
3. **Custom Implementation**: Implement specific features yourself if the polyfill doesn't cover your needs
