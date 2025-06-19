# Third-Party Package Migration Requirements

## Summary
Analysis of `/packages/` directory identified several third-party packages that require migration from FilamentPHP v3 schema patterns to v4 patterns.

## Packages Requiring Migration

### 1. awcodes/filament-curator
**Files requiring migration:**
- `src/Resources/MediaResource.php` (lines 25-31, 106, 108-109)
- `src/Components/Modals/CuratorPanel.php` (line 148)

**Migration requirements:**
- Replace `Filament\Schemas\Schema` import with `Filament\Forms\Form`
- Replace `Filament\Schemas\Components\*` imports with `Filament\Forms\Components\*`
- Update method signature from `form(Schema $schema): Schema` to `form(Form $form): Form`
- Change `$schema->components([])` to `$form->schema([])`

### 2. bezhansalleh/filament-shield
**Files requiring migration:**
- `src/Resources/RoleResource.php` (line 52)

**Migration requirements:**
- Replace `Filament\Schemas\Schema` import with `Filament\Forms\Form`
- Update method signature from `form(Schema $schema): Schema` to `form(Form $form): Form`
- Change method call pattern to use `$form->schema([])`

### 3. z3d0x/filament-fabricator
**Files requiring migration:**
- `src/Resources/PageResource.php` (line 48)

**Migration requirements:**
- Replace `Filament\Schemas\Schema` import with `Filament\Forms\Form`
- Update method signature from `form(Schema $schema): Schema` to `form(Form $form): Form`
- Change method call pattern to use `$form->schema([])`

### 4. pboivin/filament-peek (Test Files)
**Files requiring migration:**
- `tests/src/Filament/Resources/PageResource.php` (lines 23, 25)
- `tests/src/Filament/Resources/PostResource.php` (lines 21, 23)

**Migration requirements:**
- Replace `Filament\Schemas\Schema` import with `Filament\Forms\Form`
- Update method signature from `form(Schema $schema): Schema` to `form(Form $form): Form`
- Change `$schema->components([])` to `$form->schema([])`

### 5. awcodes/filament-tiptap-editor (Test Files)
**Files requiring migration:**
- `tests/src/FormsTest.php` (line 99)
- `tests/src/Resources/PageResource.php` (line 30)

**Migration requirements:**
- Replace `Filament\Schemas\Schema` import with `Filament\Forms\Form`
- Update method signature from `form(Schema $schema): Schema` to `form(Form $form): Form`
- Change method call pattern to use `$form->schema([])`

## Packages Already Using v4 Patterns
The following packages were found to already be using correct FilamentPHP v4 patterns:
- hugomyb/*
- kirschbaum-development/*
- lukas-frey/*
- saade/*
- shuvroroy/*
- dotswan/*

## Migration Priority
1. **High Priority**: Production resource files (awcodes/filament-curator, bezhansalleh/filament-shield, z3d0x/filament-fabricator)
2. **Medium Priority**: Test files (pboivin/filament-peek, awcodes/filament-tiptap-editor)

## Current Project Status
- **FilamentPHP Version**: v4.0.0-beta6 (confirmed via `php artisan about`)
- **Project Status**: Running successfully with mixed v3/v4 patterns
- **Compatibility**: Schemas package still present, suggesting compatibility layer exists

## Update Feasibility Assessment
**BLOCKED**: Third-party package migration cannot proceed until main project plugins are fully migrated.

**Blocking Issues**:
1. Base Resource class still expects old Schema signatures (`form(Schema $schema): Schema`)
2. Main project plugins (e.g., AccountResource.php) still use `Filament\Schemas\Schema` patterns
3. Framework compatibility layer maintains v3 patterns for now

**Required Prerequisites**:
1. Complete migration of all main project plugins (tasks 4.0-6.0 need verification)
2. Update base Resource class to use FilamentPHP v4 patterns
3. Ensure framework fully supports new Form/Infolist patterns

**Recommended Approach**:
1. Verify and complete main project plugin migration first
2. Update base classes and framework components
3. Then proceed with third-party package migration

## Notes
- Most schema references found were unrelated to FilamentPHP (database schemas, JSON schemas, etc.)
- No infolist methods using old schema patterns were found
- The migration follows the same patterns established for the main project plugins
- Project currently runs successfully, indicating compatibility layer exists
- Migration will improve consistency and future-proof the codebase
