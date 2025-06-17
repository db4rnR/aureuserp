# Larastan Integration
# Larastan Integration

This guide covers how to use Larastan, a PHPStan extension specifically designed for Laravel projects.

## 1. What is Larastan?

Larastan is a PHPStan extension that provides Laravel-specific static analysis rules and type definitions. It understands Laravel's dynamic features like:

- Eloquent models and relationships
- Facades
- Service container bindings
- Blade templates
- Laravel magic methods

## 2. Installation

Install Larastan using Composer:
## 1. Overview

Larastan is a PHPStan extension specifically designed for Laravel applications. It provides additional rules, type inference capabilities, and model property detection that makes static analysis more accurate and valuable for Laravel projects.

This guide covers how to effectively use Larastan with our Laravel 12 project running on PHP 8.4.

## 2. Why Larastan?

Laravel's dynamic nature, magic methods, and facades present challenges for standard static analysis. Larastan addresses these by:

- Providing type information for Laravel's magic methods
- Understanding Eloquent model property access
- Recognizing Laravel's service container bindings
- Supporting Laravel-specific patterns and conventions
- Adding specific rules for Laravel best practices

## 3. Installation

If you haven't already installed Larastan, add it to your project:
