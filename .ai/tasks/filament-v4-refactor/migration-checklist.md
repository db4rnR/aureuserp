# FilamentPHP v4 Migration Checklist

Based on comprehensive analysis of the current plugin structure, this checklist provides a systematic approach for migrating each plugin from custom schema patterns to idiomatic FilamentPHP v4.

## Migration Scope Summary

- **Total Plugins**: 20 webkul plugins
- **Form Methods**: 100+ methods using `Schema $schema` pattern
- **Infolist Methods**: 88 methods using `Schema $schema` pattern
- **Files with Schema Imports**: 100+ files using `Filament\Schemas\*` imports

## Pre-Migration Checklist

### 1. Plugin Analysis
- [ ] Identify all Resource files in the plugin
- [ ] Count form methods using `Schema $schema`
- [ ] Count infolist methods using `Schema $schema`
- [ ] List all custom components that may need migration
- [ ] Check for any custom actions using schema patterns

### 2. Backup and Safety
- [ ] Create git branch for the plugin migration
- [ ] Run existing tests to establish baseline
- [ ] Document current functionality for validation

## Migration Checklist Per Plugin

### Phase 1: Import Statement Updates

#### Form-Related Imports
- [ ] Replace `use Filament\Schemas\Schema;` with `use Filament\Forms\Form;`
- [ ] Replace `use Filament\Schemas\Components\*;` with `use Filament\Forms\Components\*;`
- [ ] Replace `use Filament\Schemas\Components\Utilities\Get;` with `use Filament\Forms\Get;`
- [ ] Replace `use Filament\Schemas\Components\Utilities\Set;` with `use Filament\Forms\Set;`

#### Infolist-Related Imports
- [ ] Replace `use Filament\Schemas\Schema;` with `use Filament\Infolists\Infolist;` (in infolist contexts)
- [ ] Replace `use Filament\Schemas\Components\Section;` with `use Filament\Infolists\Components\Section;` (in infolist contexts)
- [ ] Replace `use Filament\Schemas\Components\Grid;` with `use Filament\Infolists\Components\Grid;` (in infolist contexts)
- [ ] Replace `use Filament\Schemas\Components\Group;` with `use Filament\Infolists\Components\Group;` (in infolist contexts)

### Phase 2: Method Signature Updates

#### Form Methods
- [ ] Change `public static function form(Schema $schema): Schema` to `public static function form(Form $form): Form`
- [ ] Update method body from `return $schema->components([...])` to `return $form->schema([...])`

#### Infolist Methods
- [ ] Change `public static function infolist(Schema $schema): Schema` to `public static function infolist(Infolist $infolist): Infolist`
- [ ] Update method body from `return $schema->components([...])` to `return $infolist->schema([...])`

### Phase 3: Component Usage Updates

#### Form Components
- [ ] Verify all form components use `Filament\Forms\Components\*` namespace
- [ ] Update any custom component configurations for FilamentPHP v4 compatibility
- [ ] Test form validation and behavior

#### Infolist Components
- [ ] Verify all infolist components use `Filament\Infolists\Components\*` namespace
- [ ] Ensure display logic and formatting remain identical
- [ ] Test infolist data presentation

### Phase 4: Custom Components and Actions

#### Custom Components
- [ ] Review any custom form components for FilamentPHP v4 compatibility
- [ ] Update custom component implementations if needed
- [ ] Test custom component functionality

#### Actions
- [ ] Verify actions already use standard FilamentPHP v4 patterns
- [ ] Update any non-standard action implementations
- [ ] Test action notifications and success/error handling

### Phase 5: Testing and Validation

#### Functional Testing
- [ ] Run plugin-specific tests
- [ ] Test all forms for validation and data saving
- [ ] Test all infolists for proper data display
- [ ] Test all table functionality (filters, sorting, search)
- [ ] Test all actions and notifications

#### Integration Testing
- [ ] Test plugin integration with other plugins
- [ ] Verify no breaking changes to existing workflows
- [ ] Test database relationships and data integrity

#### Performance Testing
- [ ] Compare performance before and after migration
- [ ] Ensure no performance degradation

## Post-Migration Checklist

### Documentation Updates
- [ ] Update plugin README if it references old patterns
- [ ] Update any inline code comments referencing schemas
- [ ] Document any migration-specific notes

### Final Validation
- [ ] All tests pass
- [ ] All functionality works identically to pre-migration
- [ ] No console errors or warnings
- [ ] Code follows FilamentPHP v4 best practices

### Cleanup
- [ ] Remove any unused imports
- [ ] Clean up any temporary migration code
- [ ] Commit changes with descriptive message

## Plugin-Specific Considerations

### High-Complexity Plugins
These plugins may require additional attention due to complex schema usage:
- **employees**: Large EmployeeResource with extensive form/infolist
- **sales**: Complex QuotationResource with multiple schema patterns
- **inventories**: Multiple operation resources with complex forms
- **projects**: Complex ProjectResource and TaskResource

### Medium-Complexity Plugins
- **accounts**: Multiple financial resources
- **purchases**: Order and agreement resources
- **recruitments**: Application and candidate resources

### Lower-Complexity Plugins
- **blogs**: Simple post and category resources
- **partners**: Basic partner and contact resources
- **security**: User and role management

## Common Migration Patterns

### Pattern 1: Basic Form Migration
```php
// Before
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

public static function form(Schema $schema): Schema
{
    return $schema->components([
        Section::make()->schema([...])
    ]);
}

// After
use Filament\Forms\Form;
use Filament\Forms\Components\Section;

public static function form(Form $form): Form
{
    return $form->schema([
        Section::make()->schema([...])
    ]);
}
```

### Pattern 2: Basic Infolist Migration
```php
// Before
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

public static function infolist(Schema $schema): Schema
{
    return $schema->components([
        Section::make()->schema([...])
    ]);
}

// After
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;

public static function infolist(Infolist $infolist): Infolist
{
    return $infolist->schema([
        Section::make()->schema([...])
    ]);
}
```

This checklist ensures systematic and thorough migration of each plugin while maintaining functionality and data integrity.
