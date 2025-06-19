# FilamentPHP v4 Migration Workflow Documentation

## Overview

This document provides a comprehensive step-by-step migration process for converting plugins from FilamentPHP v3 Schema patterns to FilamentPHP v4 Form and Infolist patterns.

## Prerequisites

Before starting the migration process, ensure you have:

1. **Backup Created**: A complete backup of the current plugin state
2. **Testing Environment**: A working testing environment with all dependencies
3. **Migration Tools**: All automated migration scripts available in `.ai/tasks/filament-v4-refactor/scripts/`
4. **Documentation**: Access to FilamentPHP v4 documentation and migration patterns

## Migration Workflow

### Phase 1: Pre-Migration Preparation

#### Step 1.1: Create Plugin Backup
```bash
# Create a backup branch for the specific plugin
git checkout -b backup/pre-filament-v4-migration-{plugin-name}
git add .
git commit -m "backup: Pre-FilamentPHP v4 migration state for {plugin-name}"
git push origin backup/pre-filament-v4-migration-{plugin-name}
```

#### Step 1.2: Establish Baseline
```bash
# Run baseline tests
php .ai/tasks/filament-v4-refactor/scripts/automated-plugin-testing.php test {plugin-name} --report=baseline

# Run performance benchmark
php .ai/tasks/filament-v4-refactor/scripts/performance-comparison-tools.php benchmark plugins/webkul/{plugin-name} --output=baseline-performance.json

# Validate current state
php .ai/tasks/filament-v4-refactor/scripts/migration-completeness-validator.php plugins/webkul/{plugin-name} baseline-validation-report.md
```

#### Step 1.3: Analyze Plugin Structure
```bash
# Identify all files that need migration
find plugins/webkul/{plugin-name} -name "*.php" -exec grep -l "Filament\\Schemas" {} \;

# Count migration scope
grep -r "Filament\\Schemas" plugins/webkul/{plugin-name} --include="*.php" | wc -l
```

### Phase 2: Automated Migration

#### Step 2.1: Import Statement Migration
```bash
# Run import statement migration
php .ai/tasks/filament-v4-refactor/scripts/import-migration.php plugins/webkul/{plugin-name} import-migration-report.md

# Verify import changes
git diff --name-only
git diff plugins/webkul/{plugin-name}
```

#### Step 2.2: Method Signature Migration
```bash
# Run method signature migration
php .ai/tasks/filament-v4-refactor/scripts/method-signature-migration.php migrate plugins/webkul/{plugin-name}

# Validate method signatures
php .ai/tasks/filament-v4-refactor/scripts/method-signature-migration.php validate plugins/webkul/{plugin-name}
```

#### Step 2.3: Component Namespace Migration
```bash
# Run component namespace migration
php .ai/tasks/filament-v4-refactor/scripts/component-namespace-migration.php migrate plugins/webkul/{plugin-name}

# Validate component namespaces
php .ai/tasks/filament-v4-refactor/scripts/component-namespace-migration.php validate plugins/webkul/{plugin-name}
```

### Phase 3: Manual Review and Fixes

#### Step 3.1: Review Automated Changes
```bash
# Review all changes made by automation
git diff --stat
git diff plugins/webkul/{plugin-name}

# Check for any remaining old patterns
grep -r "Filament\\Schemas" plugins/webkul/{plugin-name} --include="*.php"
```

#### Step 3.2: Manual Pattern Updates

**Form Method Updates:**
- Ensure all `form(Schema $schema): Schema` are updated to `form(Form $form): Form`
- Update `$schema->components([])` to `$form->schema([])`
- Update `return $schema;` to `return $form;`

**Infolist Method Updates:**
- Ensure all `infolist(Schema $schema): Schema` are updated to `infolist(Infolist $infolist): Infolist`
- Update `$schema->components([])` to `$infolist->schema([])`
- Update `return $schema;` to `return $infolist;`

**Component Usage Updates:**
- Verify form components use `Filament\Forms\Components\*`
- Verify infolist components use `Filament\Infolists\Components\*`
- Update utility imports: `Get` and `Set` to `Filament\Forms\Get` and `Filament\Forms\Set`

#### Step 3.3: Custom Component Review
```bash
# Find custom components that may need updates
find plugins/webkul/{plugin-name} -name "*.php" -exec grep -l "extends.*Component" {} \;

# Review custom action implementations
find plugins/webkul/{plugin-name} -name "*.php" -exec grep -l "extends.*Action" {} \;
```

### Phase 4: Testing and Validation

#### Step 4.1: Migration Completeness Validation
```bash
# Run comprehensive migration validation
php .ai/tasks/filament-v4-refactor/scripts/migration-completeness-validator.php plugins/webkul/{plugin-name} migration-validation-report.md

# Check validation results
cat migration-validation-report.md
```

#### Step 4.2: Functional Testing
```bash
# Run plugin tests
php .ai/tasks/filament-v4-refactor/scripts/automated-plugin-testing.php test {plugin-name} --report=post-migration

# Compare test results
diff baseline-test-report.md post-migration-test-report.md
```

#### Step 4.3: Performance Testing
```bash
# Run performance benchmark
php .ai/tasks/filament-v4-refactor/scripts/performance-comparison-tools.php benchmark plugins/webkul/{plugin-name} --output=post-migration-performance.json

# Compare performance
php .ai/tasks/filament-v4-refactor/scripts/performance-comparison-tools.php compare baseline-performance.json post-migration-performance.json --report=performance-comparison.md
```

### Phase 5: Integration Testing

#### Step 5.1: Cross-Plugin Dependencies
```bash
# Test plugins that depend on this plugin
# (Refer to base-class-dependencies.md for dependency mapping)

# Run integration tests
php artisan test --filter="Integration.*{plugin-name}"
```

#### Step 5.2: Full Application Testing
```bash
# Run full test suite
php artisan test

# Check for any breaking changes
./vendor/bin/pest --coverage
```

### Phase 6: Documentation and Cleanup

#### Step 6.1: Update Plugin Documentation
- Update plugin README.md if it references old patterns
- Update inline code comments that mention Schema patterns
- Document any migration-specific notes or considerations

#### Step 6.2: Code Cleanup
```bash
# Remove any unused imports
# (This should be handled by automated tools, but verify manually)

# Clean up any temporary migration code
# Remove any debugging code added during migration
```

#### Step 6.3: Final Validation
```bash
# Run final comprehensive validation
php .ai/tasks/filament-v4-refactor/scripts/migration-completeness-validator.php plugins/webkul/{plugin-name} final-validation-report.md

# Ensure 100% migration completeness
grep "Migration Completeness: 100%" final-validation-report.md
```

### Phase 7: Commit and Documentation

#### Step 7.1: Commit Changes
```bash
# Stage all changes
git add plugins/webkul/{plugin-name}

# Commit with descriptive message
git commit -m "feat: migrate {plugin-name} to FilamentPHP v4

- Update import statements from Schema to Form/Infolist patterns
- Migrate method signatures to use Form/Infolist parameters
- Update component namespaces for v4 compatibility
- Maintain all existing functionality and validation rules
- Achieve 100% migration completeness validation

Migration includes:
- Form methods: Schema -> Form pattern
- Infolist methods: Schema -> Infolist pattern  
- Component imports: Schemas\Components -> Forms\Components / Infolists\Components
- Utility imports: Schema utilities -> Forms utilities

Testing:
- All existing tests pass
- Performance benchmarks show no regression
- Migration completeness validation: 100%

Refs: #filament-v4-migration"
```

#### Step 7.2: Update Migration Tracking
```bash
# Update the main task list
# Mark the plugin as completed in tasks-prd-filament-v4-refactor.md

# Update relevant files list
# Add any new files created during migration
```

## Rollback Procedures

### Emergency Rollback
If critical issues are discovered after migration:

```bash
# Quick rollback to backup branch
git checkout backup/pre-filament-v4-migration-{plugin-name}
git checkout -b hotfix/rollback-{plugin-name}-migration
git push origin hotfix/rollback-{plugin-name}-migration

# Merge rollback to main branch if needed
git checkout main
git merge hotfix/rollback-{plugin-name}-migration
```

### Partial Rollback
If only specific files need to be rolled back:

```bash
# Rollback specific files
git checkout backup/pre-filament-v4-migration-{plugin-name} -- plugins/webkul/{plugin-name}/src/Filament/Resources/SpecificResource.php

# Commit partial rollback
git commit -m "rollback: revert SpecificResource.php to pre-migration state"
```

## Quality Assurance Checklist

Before marking a plugin migration as complete, ensure:

- [ ] **Migration Completeness**: 100% validation score
- [ ] **Test Coverage**: All existing tests pass
- [ ] **Performance**: No significant performance regression (>5%)
- [ ] **Functionality**: All forms, infolists, and tables work correctly
- [ ] **Actions**: All custom actions function properly
- [ ] **Validation**: Form validation rules work as expected
- [ ] **Dependencies**: No breaking changes to dependent plugins
- [ ] **Documentation**: Plugin documentation updated if needed
- [ ] **Code Quality**: No unused imports or temporary code
- [ ] **Git History**: Clean commit with descriptive message

## Common Issues and Solutions

### Issue: Mixed Import Patterns
**Problem**: File contains both old and new import patterns
**Solution**: 
```bash
# Re-run import migration with force flag
php .ai/tasks/filament-v4-refactor/scripts/import-migration.php plugins/webkul/{plugin-name} --force
```

### Issue: Method Signature Mismatch
**Problem**: Method signature not properly updated
**Solution**: Manual review and update of method signatures

### Issue: Component Not Found Errors
**Problem**: Component class not found after namespace change
**Solution**: Verify component exists in new namespace, update import if needed

### Issue: Test Failures
**Problem**: Tests fail after migration
**Solution**: 
1. Check if test files also need migration
2. Update test mocks to use new patterns
3. Verify test data compatibility

### Issue: Performance Regression
**Problem**: Significant performance decrease after migration
**Solution**:
1. Review component usage patterns
2. Check for inefficient component configurations
3. Compare with baseline performance metrics

## Plugin-Specific Considerations

### High-Priority Plugins (accounts, contacts, partners)
- Extra validation required due to core functionality
- Cross-plugin dependency testing essential
- Performance monitoring critical

### Financial Plugins (invoices, payments, purchases)
- Data integrity validation required
- Transaction processing testing essential
- Backup verification critical

### Complex Plugins (with custom components)
- Manual review of custom component implementations
- Extended testing period recommended
- Documentation updates essential

## Migration Timeline

**Estimated time per plugin:**
- Simple plugins (5-10 resources): 2-4 hours
- Medium plugins (10-20 resources): 4-8 hours  
- Complex plugins (20+ resources): 8-16 hours

**Total estimated timeline for all 22 plugins:**
- Phase 1 (Foundation): 1-2 weeks
- Phase 2 (Financial): 1-2 weeks
- Phase 3 (Operations): 2-3 weeks
- Phase 4 (HR): 1-2 weeks
- Phase 5 (Supporting): 1-2 weeks

**Total project timeline: 6-11 weeks**

## Success Criteria

A plugin migration is considered successful when:

1. **100% Migration Completeness**: No old patterns remain
2. **100% Test Pass Rate**: All existing tests pass
3. **Performance Maintained**: <5% performance change
4. **Functionality Preserved**: All features work identically
5. **Documentation Updated**: All references to old patterns updated
6. **Clean Code**: No unused imports or temporary code
7. **Git History**: Clean, descriptive commit messages

## Support and Resources

- **Migration Patterns**: `.ai/tasks/filament-v4-refactor/migration-patterns.md`
- **Plugin Dependencies**: `.ai/tasks/filament-v4-refactor/base-class-dependencies.md`
- **Testing Guidelines**: `.ai/tasks/filament-v4-refactor/testing-environment-setup.md`
- **Backup Strategy**: `.ai/tasks/filament-v4-refactor/plugin-backup-strategy.md`
- **FilamentPHP v4 Documentation**: https://filamentphp.com/docs/3.x/upgrade-guide
