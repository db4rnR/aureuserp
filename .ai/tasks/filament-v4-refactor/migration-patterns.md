# FilamentPHP v4 Migration Patterns

Based on analysis of the current plugin structure, here are the key migration patterns identified:

## Current Schema Usage Patterns

### 1. Form Methods
**Current Pattern:**
```php
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
// ... other schema components

public static function form(Schema $schema): Schema
{
    return $schema
        ->components([
            Section::make()
                ->schema([
                    // form components
                ])
        ]);
}
```

**Required Migration to FilamentPHP v4:**
```php
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
// ... other form components

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make()
                ->schema([
                    // form components
                ])
        ]);
}
```

### 2. Infolist Methods
**Current Pattern:**
```php
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

public static function infolist(Schema $schema): Schema
{
    return $schema
        ->components([
            Section::make()
                ->schema([
                    // infolist entries
                ])
        ]);
}
```

**Required Migration to FilamentPHP v4:**
```php
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;

public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            Section::make()
                ->schema([
                    // infolist entries
                ])
        ]);
}
```

### 3. Import Statement Changes

**Schema Components to Form Components:**
- `Filament\Schemas\Components\*` → `Filament\Forms\Components\*`
- `Filament\Schemas\Components\Utilities\Get` → `Filament\Forms\Get`
- `Filament\Schemas\Components\Utilities\Set` → `Filament\Forms\Set`

**Schema Components to Infolist Components:**
- `Filament\Schemas\Components\Section` → `Filament\Infolists\Components\Section`
- `Filament\Schemas\Components\Grid` → `Filament\Infolists\Components\Grid`
- `Filament\Schemas\Components\Group` → `Filament\Infolists\Components\Group`

### 4. Method Signature Changes

**Form Methods:**
- Parameter: `Schema $schema` → `Form $form`
- Return type: `Schema` → `Form`
- Method call: `$schema->components()` → `$form->schema()`

**Infolist Methods:**
- Parameter: `Schema $schema` → `Infolist $infolist`
- Return type: `Schema` → `Infolist`
- Method call: `$schema->components()` → `$infolist->schema()`

## Files Already Using Standard FilamentPHP v4 Patterns

### Table Methods
- Already use `Filament\Tables\Table` parameter and return type
- Already use standard `Filament\Tables\Columns\*` components
- No migration needed for table implementations

### Actions
- Already use standard FilamentPHP v4 action classes:
  - `Filament\Actions\ViewAction`
  - `Filament\Actions\EditAction`
  - `Filament\Actions\DeleteAction`
  - `Filament\Actions\BulkActionGroup`
  - `Filament\Actions\DeleteBulkAction`

## Affected Plugin Count
Based on search results, over 100 files across all 20 webkul plugins use `Filament\Schemas` patterns and require migration.

## Migration Complexity Assessment
- **Low Complexity**: Import statement updates and method signature changes
- **Medium Complexity**: Component namespace changes and method call updates
- **High Complexity**: Custom schema components that may need complete rewriting

This analysis provides the foundation for implementing the remaining migration tasks.
