# Plugin-Specific Migration Templates

## Overview

This document provides customized migration templates for different types of plugins based on their complexity, dependencies, and characteristics. Each template is tailored to address the specific challenges and requirements of different plugin categories.

**Last Updated:** December 19, 2024  
**Purpose:** Provide comprehensive, actionable migration templates for FilamentPHP v4 refactoring

## Template Categories

### 1. Foundation Plugins Template
**Applicable to**: accounts, contacts, partners
**Characteristics**: Core functionality, high interdependency, critical business logic

### 2. Financial Plugins Template  
**Applicable to**: invoices, payments, purchases
**Characteristics**: Financial data handling, transaction processing, audit requirements

### 3. Operations Plugins Template
**Applicable to**: products, inventories, sales
**Characteristics**: Business operations, data relationships, workflow dependencies

### 4. HR Plugins Template
**Applicable to**: employees, recruitments, time-off, timesheets
**Characteristics**: Employee data, compliance requirements, reporting features

### 5. Supporting Plugins Template
**Applicable to**: analytics, blogs, chatter, fields, projects, security, support, table-views, website
**Characteristics**: Auxiliary features, lower complexity, fewer dependencies

---

## Template 1: Foundation Plugins Migration

### Pre-Migration Checklist
- [ ] **Critical**: Create multiple backup points
- [ ] **Critical**: Notify all team members of migration
- [ ] **Critical**: Schedule maintenance window
- [ ] **Critical**: Prepare rollback plan
- [ ] **Required**: Test all dependent plugins
- [ ] **Required**: Validate data integrity procedures

#### Pre-Migration Checklist
- [ ] Verify no other plugins are currently being migrated
- [ ] Confirm all dependent plugins are identified and documented
- [ ] Establish performance baseline for this plugin
- [ ] Create plugin-specific backup branch
- [ ] Notify team of foundation plugin migration start

#### Phase 1: Pre-Migration Analysis
```bash
# Navigate to plugin directory
cd plugins/webkul/[PLUGIN_NAME]

# Analyze FilamentPHP usage
find . -name "*.php" -exec grep -l "Filament\\Schemas" {} \; > /tmp/schema_files.txt
find . -name "*.php" -exec grep -l "function form(" {} \; > /tmp/form_files.txt
find . -name "*.php" -exec grep -l "function infolist(" {} \; > /tmp/infolist_files.txt

# Check for extensions by other plugins
grep -r "extends.*[PLUGIN_NAME]" ../../../ > /tmp/dependent_plugins.txt

# Document current resource count
find src/Filament/Resources -name "*Resource.php" | wc -l
```

**Expected Analysis Results:**
- Number of files with Schema usage: ___
- Number of form methods: ___
- Number of infolist methods: ___
- Number of dependent plugins: ___
- Estimated migration time: ___ hours

#### Phase 2: Dependency Impact Assessment
```bash
# Check which plugins extend from this plugin's resources
grep -r "use Webkul\\[PLUGIN_NAME]" ../../../plugins/webkul/*/src/

# Document inheritance chains
grep -r "extends.*Resource" src/Filament/Resources/ | grep -v "extends Resource"
```

**Dependency Documentation:**
- Plugins that extend this plugin's resources: ___
- Resources that are extended by other plugins: ___
- Critical inheritance chains: ___

#### Phase 3: Migration Execution

##### Step 3.1: Import Migration
```bash
# Run automated import updates
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/import-updater.php .

# Validate import changes
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/migration-completeness-validator.php ../../../ --plugin=[PLUGIN_NAME]
```

##### Step 3.2: Method Signature Migration
```bash
# Run automated signature updates
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/method-signature-updater.php .

# Manual verification of critical resources
# [List critical resources that need manual verification]
```

##### Step 3.3: Component Migration
```bash
# Run component migration utilities
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/component-migration-utilities.php migrate .
```

#### Phase 4: Foundation-Specific Validation

##### Step 4.1: Extended Resource Validation
For each resource that is extended by other plugins:
- [ ] Verify method signatures are compatible
- [ ] Check that inheritance still works
- [ ] Test with a sample dependent plugin
- [ ] Document any breaking changes

##### Step 4.2: Critical Functionality Testing
Foundation plugins require extra testing:
- [ ] Test all CRUD operations
- [ ] Verify data relationships work
- [ ] Check permission systems
- [ ] Test with sample data from dependent plugins

#### Phase 5: Dependent Plugin Impact Assessment
```bash
# Test impact on dependent plugins (without migrating them)
for plugin in [LIST_OF_DEPENDENT_PLUGINS]; do
    echo "Testing impact on $plugin"
    php artisan route:list | grep $plugin
    # Add specific tests for each dependent plugin
done
```

#### Phase 6: Foundation Plugin Completion
- [ ] All tests passing
- [ ] Performance within baseline
- [ ] No breaking changes to inheritance
- [ ] Documentation updated
- [ ] Dependent plugins still functional
- [ ] Team notified of completion

**Foundation Plugin Success Criteria:**
- Zero breaking changes to dependent plugins
- All inheritance chains preserved
- Performance maintained or improved
- 100% test coverage maintained

---

## Tier 2: Intermediate Plugins Migration Template

### Plugin: [PLUGIN_NAME] (Intermediate Tier)

#### Pre-Migration Checklist
- [ ] Verify all Tier 1 dependencies are migrated
- [ ] Confirm foundation plugins are stable
- [ ] Check for any Tier 2 interdependencies
- [ ] Establish plugin-specific baseline

#### Phase 1: Dependency Verification
```bash
# Verify Tier 1 dependencies are migrated
for tier1_plugin in partners products employees accounts; do
    echo "Checking $tier1_plugin migration status"
    php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/migration-completeness-validator.php ../../../ --plugin=$tier1_plugin
done
```

#### Phase 2: Intermediate-Specific Analysis
```bash
# Check for complex inheritance patterns
grep -r "extends.*Resource" src/ | grep -v "extends Resource"

# Analyze relationship dependencies
grep -r "relationship(" src/Filament/Resources/
grep -r "belongsTo\|hasMany\|hasOne" src/Models/
```

#### Phase 3: Migration with Dependency Awareness
```bash
# Run migration with dependency checking
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/import-updater.php .
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/method-signature-updater.php .

# Validate against Tier 1 plugins
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/migration-completeness-validator.php ../../../ --plugin=[PLUGIN_NAME] --strict
```

#### Phase 4: Relationship Testing
- [ ] Test all relationships with Tier 1 plugins
- [ ] Verify data integrity across plugin boundaries
- [ ] Check form relationships still work
- [ ] Test infolist data display from related plugins

#### Phase 5: Integration Validation
```bash
# Test integration with foundation plugins
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/automated-plugin-testing.php test --plugin=[PLUGIN_NAME] --type=integration
```

---

## Tier 3: Advanced Plugins Migration Template

### Plugin: [PLUGIN_NAME] (Advanced Tier)

#### Pre-Migration Checklist
- [ ] Verify Tier 1 & 2 dependencies are migrated and stable
- [ ] Identify complex dependency chains
- [ ] Plan for extended testing period
- [ ] Prepare rollback strategy for complex dependencies

#### Phase 1: Complex Dependency Analysis
```bash
# Map all dependencies
echo "Analyzing complex dependencies for [PLUGIN_NAME]"
grep -r "use Webkul\\" src/ | sort | uniq

# Check for circular dependencies
grep -r "extends.*[PLUGIN_NAME]" ../../../plugins/webkul/*/src/
```

#### Phase 2: Staged Migration Approach
Due to complexity, use staged approach:

##### Stage 1: Core Resources
- Migrate basic resources without complex relationships
- Test core functionality

##### Stage 2: Relationship Resources
- Migrate resources with relationships to other plugins
- Test integration points

##### Stage 3: Complex Features
- Migrate advanced features and custom components
- Comprehensive testing

#### Phase 3: Extended Testing Protocol
```bash
# Run comprehensive test suite
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/automated-plugin-testing.php test --plugin=[PLUGIN_NAME] --type=all --coverage

# Performance testing with complex data
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/performance-comparison-tools.php benchmark --plugin=[PLUGIN_NAME]
```

#### Phase 4: Multi-Plugin Integration Testing
- [ ] Test with all Tier 1 plugins
- [ ] Test with all Tier 2 plugins
- [ ] Test complex workflows across multiple plugins
- [ ] Verify data consistency across plugin boundaries

---

## Tier 4: Specialized Plugins Migration Template

### Plugin: [PLUGIN_NAME] (Specialized Tier)

#### Pre-Migration Checklist
- [ ] Verify specific dependencies are migrated
- [ ] Understand specialized functionality requirements
- [ ] Plan for unique testing scenarios

#### Phase 1: Specialized Feature Analysis
```bash
# Analyze specialized components
find src/ -name "*.php" -exec grep -l "Widget\|Dashboard\|Custom" {} \;

# Check for specialized integrations
grep -r "extends.*Widget\|extends.*Dashboard" src/
```

#### Phase 2: Feature-Specific Migration
- Focus on specialized features that may need custom handling
- Test specialized integrations thoroughly
- Verify unique functionality is preserved

#### Phase 3: Specialized Testing
```bash
# Test specialized features
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/automated-plugin-testing.php test --plugin=[PLUGIN_NAME] --type=feature
```

---

## Tier 5: Independent Plugins Migration Template

### Plugin: [PLUGIN_NAME] (Independent Tier)

#### Pre-Migration Checklist
- [ ] Confirm plugin has no dependencies
- [ ] Verify plugin doesn't affect others
- [ ] Plan for quick migration

#### Phase 1: Independence Verification
```bash
# Verify no dependencies
grep -r "use Webkul\\" src/ | grep -v "use Webkul\\[PLUGIN_NAME]"

# Confirm no other plugins depend on this one
grep -r "use Webkul\\[PLUGIN_NAME]" ../../../plugins/webkul/*/src/
```

#### Phase 2: Rapid Migration
```bash
# Quick migration process
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/import-updater.php .
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/method-signature-updater.php .
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/component-migration-utilities.php migrate .
```

#### Phase 3: Independent Testing
```bash
# Quick validation
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/migration-completeness-validator.php ../../../ --plugin=[PLUGIN_NAME]
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/automated-plugin-testing.php test --plugin=[PLUGIN_NAME]
```

---

## Special Case Templates

### Sales Plugin (Most Complex)

#### Extended Migration Template
Due to the Sales plugin's extensive dependencies, use this extended template:

##### Pre-Migration Requirements
- [ ] All Tier 1 plugins migrated and stable
- [ ] Partners plugin fully tested
- [ ] Products plugin fully tested
- [ ] Employees plugin fully tested
- [ ] Extended testing environment prepared

##### Phase 1: Dependency Mapping
```bash
# Map all Sales plugin dependencies
echo "Sales Plugin Dependency Analysis"
grep -r "use Webkul\\" src/ | grep -v "use Webkul\\Sales" | sort | uniq > /tmp/sales_dependencies.txt

# Analyze inheritance chains
grep -r "extends.*Resource" src/Filament/Resources/ | grep -v "extends Resource" > /tmp/sales_inheritance.txt
```

##### Phase 2: Staged Migration (Extended)
1. **Stage 1:** Basic sales resources (2-4 hours)
2. **Stage 2:** Customer-related resources (4-6 hours)
3. **Stage 3:** Product-related resources (4-6 hours)
4. **Stage 4:** Order processing resources (6-8 hours)
5. **Stage 5:** Integration testing (4-6 hours)

##### Phase 3: Comprehensive Testing
```bash
# Extended testing protocol
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/automated-plugin-testing.php test --plugin=sales --type=all --coverage --stop-on-failure

# Performance testing with large datasets
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/performance-comparison-tools.php benchmark --plugin=sales --iterations=20
```

---

## Template Usage Instructions

### How to Use These Templates

1. **Select Appropriate Template**
   - Identify plugin tier from base-class-dependencies.md
   - Choose corresponding template

2. **Customize Template**
   - Replace [PLUGIN_NAME] with actual plugin name
   - Fill in expected analysis results
   - Adjust commands for plugin-specific paths

3. **Execute Template Steps**
   - Follow steps in order
   - Complete all checkboxes
   - Document any deviations

4. **Validate Completion**
   - Ensure all success criteria met
   - Run final validation scripts
   - Update task list

### Template Customization Guidelines

#### For High-Dependency Plugins
- Add extra dependency verification steps
- Include additional integration testing
- Plan for longer migration windows

#### For Simple Plugins
- Streamline testing procedures
- Reduce validation steps
- Focus on core functionality

#### For Custom Components
- Add component-specific testing
- Include custom validation steps
- Plan for manual verification

### Common Template Modifications

#### Adding Custom Validation
```bash
# Add plugin-specific validation
echo "Running custom validation for [PLUGIN_NAME]"
# Add custom commands here
```

#### Extending Testing
```bash
# Add extended testing for complex plugins
php custom-test-script.php --plugin=[PLUGIN_NAME]
```

#### Performance Monitoring
```bash
# Add performance monitoring for critical plugins
php ../../../.ai/tasks/filament-v4-refactor/migration-scripts/performance-comparison-tools.php monitor --plugin=[PLUGIN_NAME]
```

## Template Maintenance

### Updating Templates
- Update templates based on migration experience
- Add new validation steps as needed
- Incorporate lessons learned from completed migrations

### Template Versioning
- Version templates with migration progress
- Document template changes
- Maintain backward compatibility

### Quality Assurance
- Test templates with sample plugins
- Validate template completeness
- Ensure all edge cases covered

## Notes

- Templates should be customized for each plugin's specific needs
- Always verify dependencies before starting migration
- Document any deviations from template procedures
- Update templates based on migration experience
- Maintain detailed logs of template usage and outcomes

These templates provide a structured approach to plugin migration while allowing for customization based on each plugin's unique characteristics and requirements.
