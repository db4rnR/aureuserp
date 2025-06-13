# Custom PHPStan Rules

## 1. Introduction to Custom Rules

PHPStan allows extending its functionality with custom rules to enforce project-specific standards. This document covers how we create and use custom rules in our Laravel 12 project with PHP 8.4.

## 2. Why Create Custom Rules?

Custom rules help us:

- Enforce project-specific conventions
- Prevent common mistakes in our codebase
- Automate code reviews for specific patterns
- Ensure consistency across the team

## 3. Rule Structure
# PHPStan Custom Rules

This guide covers how to create and use custom PHPStan rules for our Laravel 12 project.

## 1. Introduction to Custom Rules

Custom PHPStan rules allow you to:

- Enforce project-specific coding standards
- Prevent common architectural mistakes
- Detect business logic errors
- Maintain consistent patterns across the codebase

## 2. Custom Rule Structure

### 2.1. Basic Rule Structure

A PHPStan rule class:
A PHPStan rule consists of:

1. A class implementing `PHPStan\Rules\Rule`
2. A `getNodeType()` method specifying what code elements to analyze
3. A `processNode()` method that performs the actual analysis

## 4. Example Custom Rules

### 4.1. Controller Action Return Type Rule

This rule ensures all controller actions return a proper response type:
