# Rollback and Recovery Procedures - FilamentPHP v4 Migration

**Date Created:** December 19, 2024  
**Purpose:** Establish comprehensive rollback and recovery procedures for the FilamentPHP v4 migration process

## Overview

This document provides detailed procedures for rolling back migrations when issues are encountered, ensuring system stability and data integrity. It includes automated rollback scripts, manual recovery procedures, and emergency protocols.

## Rollback Strategy Framework

### Rollback Triggers

#### Critical Issues (Immediate Rollback Required)
- Fatal PHP errors preventing application load
- Database corruption or data loss
- Authentication system failures
- Complete plugin functionality breakdown
- Security vulnerabilities introduced
- Performance degradation >50%

#### High Priority Issues (Rollback Recommended)
- Multiple form submission failures
- Significant data integrity issues
- Major UI/UX functionality loss
- Performance degradation 20-50%
- Multiple test failures

#### Medium Priority Issues (Rollback Optional)
- Minor functionality issues
- Performance degradation 10-20%
- Non-critical UI problems
- Limited test failures

### Rollback Scope Levels

#### Level 1: Single Plugin Rollback
- Rollback specific plugin to pre-migration state
- Preserve other migrated plugins
- Minimal system impact

#### Level 2: Tier-Based Rollback
- Rollback entire plugin tier (e.g., all Tier 1 plugins)
- Maintain dependency integrity
- Coordinated rollback approach

#### Level 3: Complete System Rollback
- Rollback entire system to pre-migration state
- Emergency procedure only
- Full system restoration

## Automated Rollback Scripts

### Plugin-Level Rollback Script

```bash
#!/bin/bash
# File: .ai/tasks/filament-v4-refactor/scripts/rollback-plugin.sh

set -e

PLUGIN_NAME="$1"
BACKUP_BRANCH="backup/pre-filament-v4-migration"
ROLLBACK_LOG=".ai/tasks/filament-v4-refactor/logs/rollback-$(date +%Y%m%d-%H%M%S).log"

if [ -z "$PLUGIN_NAME" ]; then
    echo "Usage: $0 <plugin-name>"
    exit 1
fi

echo "Starting rollback for plugin: $PLUGIN_NAME" | tee -a "$ROLLBACK_LOG"
echo "Timestamp: $(date)" | tee -a "$ROLLBACK_LOG"

# Step 1: Verify backup branch exists
echo "Verifying backup branch..." | tee -a "$ROLLBACK_LOG"
if ! git show-ref --verify --quiet refs/heads/$BACKUP_BRANCH; then
    echo "ERROR: Backup branch $BACKUP_BRANCH not found!" | tee -a "$ROLLBACK_LOG"
    exit 1
fi

# Step 2: Check current git status
echo "Checking git status..." | tee -a "$ROLLBACK_LOG"
if [ -n "$(git status --porcelain)" ]; then
    echo "WARNING: Uncommitted changes detected. Stashing..." | tee -a "$ROLLBACK_LOG"
    git stash push -m "Pre-rollback stash $(date)"
fi

# Step 3: Create rollback point
echo "Creating rollback point..." | tee -a "$ROLLBACK_LOG"
CURRENT_COMMIT=$(git rev-parse HEAD)
echo "Current commit: $CURRENT_COMMIT" | tee -a "$ROLLBACK_LOG"

# Step 4: Rollback plugin files
echo "Rolling back plugin files..." | tee -a "$ROLLBACK_LOG"
git checkout $BACKUP_BRANCH -- plugins/webkul/$PLUGIN_NAME/

# Step 5: Verify rollback
echo "Verifying rollback..." | tee -a "$ROLLBACK_LOG"
if [ -d "plugins/webkul/$PLUGIN_NAME" ]; then
    echo "Plugin directory restored successfully" | tee -a "$ROLLBACK_LOG"
else
    echo "ERROR: Plugin directory not found after rollback!" | tee -a "$ROLLBACK_LOG"
    exit 1
fi

# Step 6: Clear caches
echo "Clearing caches..." | tee -a "$ROLLBACK_LOG"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Step 7: Run basic validation
echo "Running basic validation..." | tee -a "$ROLLBACK_LOG"
php artisan route:list | grep -i $PLUGIN_NAME || echo "No routes found for $PLUGIN_NAME"

# Step 8: Test plugin functionality
echo "Testing plugin functionality..." | tee -a "$ROLLBACK_LOG"
if [ -d "tests/Unit/Plugins/$(echo $PLUGIN_NAME | sed 's/\b\w/\U&/g')" ]; then
    ./vendor/bin/pest tests/Unit/Plugins/$(echo $PLUGIN_NAME | sed 's/\b\w/\U&/g')/ || echo "Tests failed - manual verification required"
fi

echo "Rollback completed for plugin: $PLUGIN_NAME" | tee -a "$ROLLBACK_LOG"
echo "Rollback log saved to: $ROLLBACK_LOG"
```

### Tier-Based Rollback Script

```bash
#!/bin/bash
# File: .ai/tasks/filament-v4-refactor/scripts/rollback-tier.sh

set -e

TIER="$1"
BACKUP_BRANCH="backup/pre-filament-v4-migration"
ROLLBACK_LOG=".ai/tasks/filament-v4-refactor/logs/tier-rollback-$(date +%Y%m%d-%H%M%S).log"

# Define tier plugins
declare -A TIER_PLUGINS
TIER_PLUGINS[1]="partners products employees accounts"
TIER_PLUGINS[2]="contacts invoices recruitments"
TIER_PLUGINS[3]="sales purchases inventories"
TIER_PLUGINS[4]="website projects time-off"
TIER_PLUGINS[5]="analytics blogs chatter fields security support table-views timesheets"

if [ -z "$TIER" ] || [ -z "${TIER_PLUGINS[$TIER]}" ]; then
    echo "Usage: $0 <tier-number>"
    echo "Available tiers: 1, 2, 3, 4, 5"
    exit 1
fi

echo "Starting tier $TIER rollback..." | tee -a "$ROLLBACK_LOG"
echo "Plugins: ${TIER_PLUGINS[$TIER]}" | tee -a "$ROLLBACK_LOG"

# Rollback each plugin in the tier
for plugin in ${TIER_PLUGINS[$TIER]}; do
    echo "Rolling back plugin: $plugin" | tee -a "$ROLLBACK_LOG"
    ./.ai/tasks/filament-v4-refactor/scripts/rollback-plugin.sh "$plugin" || {
        echo "ERROR: Failed to rollback plugin $plugin" | tee -a "$ROLLBACK_LOG"
        exit 1
    }
done

echo "Tier $TIER rollback completed successfully" | tee -a "$ROLLBACK_LOG"
```

### Complete System Rollback Script

```bash
#!/bin/bash
# File: .ai/tasks/filament-v4-refactor/scripts/emergency-rollback.sh

set -e

BACKUP_BRANCH="backup/pre-filament-v4-migration"
ROLLBACK_LOG=".ai/tasks/filament-v4-refactor/logs/emergency-rollback-$(date +%Y%m%d-%H%M%S).log"

echo "EMERGENCY SYSTEM ROLLBACK INITIATED" | tee -a "$ROLLBACK_LOG"
echo "Timestamp: $(date)" | tee -a "$ROLLBACK_LOG"

# Step 1: Confirm emergency rollback
read -p "This will rollback the entire system. Are you sure? (yes/no): " confirm
if [ "$confirm" != "yes" ]; then
    echo "Emergency rollback cancelled" | tee -a "$ROLLBACK_LOG"
    exit 0
fi

# Step 2: Create emergency backup of current state
echo "Creating emergency backup of current state..." | tee -a "$ROLLBACK_LOG"
git branch "emergency-backup-$(date +%Y%m%d-%H%M%S)" || echo "Failed to create emergency backup branch"

# Step 3: Switch to backup branch
echo "Switching to backup branch..." | tee -a "$ROLLBACK_LOG"
git checkout $BACKUP_BRANCH

# Step 4: Force reset to backup state
echo "Resetting to backup state..." | tee -a "$ROLLBACK_LOG"
git reset --hard HEAD

# Step 5: Clear all caches
echo "Clearing all caches..." | tee -a "$ROLLBACK_LOG"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Step 6: Restart services (if applicable)
echo "Restarting services..." | tee -a "$ROLLBACK_LOG"
# Add service restart commands as needed
# systemctl restart nginx
# systemctl restart php-fpm

# Step 7: Validate system
echo "Validating system..." | tee -a "$ROLLBACK_LOG"
php artisan route:list > /dev/null || echo "Route validation failed"

echo "EMERGENCY ROLLBACK COMPLETED" | tee -a "$ROLLBACK_LOG"
echo "System restored to pre-migration state"
```

## Manual Recovery Procedures

### Plugin Recovery Checklist

#### Step 1: Assess Damage
- [ ] Identify specific issues encountered
- [ ] Document error messages and symptoms
- [ ] Determine scope of impact
- [ ] Check logs for additional information

#### Step 2: Determine Rollback Scope
- [ ] Single plugin rollback sufficient?
- [ ] Multiple plugins affected?
- [ ] System-wide issues present?
- [ ] Dependencies need consideration?

#### Step 3: Execute Rollback
```bash
# For single plugin
./.ai/tasks/filament-v4-refactor/scripts/rollback-plugin.sh [plugin-name]

# For tier rollback
./.ai/tasks/filament-v4-refactor/scripts/rollback-tier.sh [tier-number]

# For emergency rollback
./.ai/tasks/filament-v4-refactor/scripts/emergency-rollback.sh
```

#### Step 4: Verify Rollback Success
- [ ] Application loads without errors
- [ ] Plugin functionality restored
- [ ] No data corruption detected
- [ ] Performance back to baseline
- [ ] Tests passing

#### Step 5: Post-Rollback Analysis
- [ ] Document what went wrong
- [ ] Identify root cause
- [ ] Plan corrective actions
- [ ] Update migration approach
- [ ] Communicate to stakeholders

### Database Recovery Procedures

#### Database Backup Strategy
```bash
# Create database backup before migration
mysqldump -u [username] -p[password] [database_name] > backup_pre_migration_$(date +%Y%m%d).sql

# Restore database if needed
mysql -u [username] -p[password] [database_name] < backup_pre_migration_$(date +%Y%m%d).sql
```

#### Database Validation
```sql
-- Check table integrity
CHECK TABLE users, plugins, migrations;

-- Verify data consistency
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM [critical_tables];

-- Check for corruption
REPAIR TABLE [table_name];
```

### File System Recovery

#### File Backup Verification
```bash
# Verify backup integrity
find plugins/webkul/ -name "*.php" -exec php -l {} \; | grep -v "No syntax errors"

# Check file permissions
find plugins/webkul/ -type f -name "*.php" ! -perm 644 -exec chmod 644 {} \;
find plugins/webkul/ -type d ! -perm 755 -exec chmod 755 {} \;
```

#### Configuration Recovery
```bash
# Restore configuration files
cp .env.backup .env
cp config/app.php.backup config/app.php

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Emergency Protocols

### Severity Level 1: Critical System Failure

#### Immediate Actions (0-5 minutes)
1. **Stop Migration Process**
   ```bash
   # Kill any running migration processes
   pkill -f "migration-scripts"
   ```

2. **Assess System State**
   ```bash
   # Check if application is accessible
   curl -I http://localhost/admin
   
   # Check for fatal errors
   tail -n 50 storage/logs/laravel.log
   ```

3. **Execute Emergency Rollback**
   ```bash
   ./.ai/tasks/filament-v4-refactor/scripts/emergency-rollback.sh
   ```

#### Follow-up Actions (5-30 minutes)
1. **Validate System Recovery**
   - Test critical user workflows
   - Verify data integrity
   - Check performance metrics

2. **Notify Stakeholders**
   - Inform team of rollback
   - Provide status update
   - Schedule post-incident review

### Severity Level 2: Plugin-Specific Issues

#### Immediate Actions (0-10 minutes)
1. **Isolate Problem Plugin**
   ```bash
   # Rollback specific plugin
   ./.ai/tasks/filament-v4-refactor/scripts/rollback-plugin.sh [plugin-name]
   ```

2. **Validate Other Plugins**
   ```bash
   # Test other plugins still work
   php artisan route:list | grep -v [problem-plugin]
   ```

#### Follow-up Actions (10-60 minutes)
1. **Analyze Root Cause**
   - Review migration logs
   - Check for dependency issues
   - Identify specific failure point

2. **Plan Corrective Action**
   - Update migration approach
   - Address identified issues
   - Schedule retry

### Severity Level 3: Performance Issues

#### Immediate Actions (0-15 minutes)
1. **Monitor Performance**
   ```bash
   # Check current performance
   php .ai/tasks/filament-v4-refactor/migration-scripts/performance-comparison-tools.php measure
   ```

2. **Determine Rollback Need**
   - Compare with baseline
   - Assess user impact
   - Decide on rollback necessity

## Recovery Validation Procedures

### Post-Rollback Validation Checklist

#### System Level Validation
- [ ] Application loads successfully
- [ ] No fatal PHP errors
- [ ] Database connectivity working
- [ ] File permissions correct
- [ ] Services running properly

#### Plugin Level Validation
- [ ] All plugin routes accessible
- [ ] CRUD operations working
- [ ] Form submissions successful
- [ ] Data display correct
- [ ] Permissions enforced

#### Data Integrity Validation
- [ ] No data corruption detected
- [ ] Relationships intact
- [ ] Counts match expectations
- [ ] No orphaned records

#### Performance Validation
- [ ] Response times acceptable
- [ ] Memory usage normal
- [ ] Database queries efficient
- [ ] No resource leaks

### Automated Validation Script

```bash
#!/bin/bash
# File: .ai/tasks/filament-v4-refactor/scripts/validate-rollback.sh

PLUGIN_NAME="$1"
VALIDATION_LOG=".ai/tasks/filament-v4-refactor/logs/validation-$(date +%Y%m%d-%H%M%S).log"

echo "Starting rollback validation for: $PLUGIN_NAME" | tee -a "$VALIDATION_LOG"

# Test 1: PHP Syntax Check
echo "Checking PHP syntax..." | tee -a "$VALIDATION_LOG"
find plugins/webkul/$PLUGIN_NAME -name "*.php" -exec php -l {} \; | grep -v "No syntax errors" && {
    echo "FAIL: PHP syntax errors found" | tee -a "$VALIDATION_LOG"
    exit 1
}

# Test 2: Route Accessibility
echo "Checking routes..." | tee -a "$VALIDATION_LOG"
php artisan route:list | grep -i $PLUGIN_NAME > /dev/null || {
    echo "WARNING: No routes found for $PLUGIN_NAME" | tee -a "$VALIDATION_LOG"
}

# Test 3: Database Connectivity
echo "Checking database..." | tee -a "$VALIDATION_LOG"
php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null || {
    echo "FAIL: Database connection failed" | tee -a "$VALIDATION_LOG"
    exit 1
}

# Test 4: Plugin Tests
echo "Running plugin tests..." | tee -a "$VALIDATION_LOG"
if [ -d "tests/Unit/Plugins/$(echo $PLUGIN_NAME | sed 's/\b\w/\U&/g')" ]; then
    ./vendor/bin/pest tests/Unit/Plugins/$(echo $PLUGIN_NAME | sed 's/\b\w/\U&/g')/ || {
        echo "FAIL: Plugin tests failed" | tee -a "$VALIDATION_LOG"
        exit 1
    }
fi

echo "Rollback validation completed successfully" | tee -a "$VALIDATION_LOG"
```

## Recovery Documentation

### Incident Report Template

```markdown
# Migration Rollback Incident Report

**Date:** [DATE]
**Time:** [TIME]
**Reporter:** [NAME]
**Severity:** [Critical/High/Medium/Low]

## Issue Description
[Detailed description of the issue that triggered rollback]

## Affected Components
- Plugin(s): [LIST]
- Functionality: [DESCRIPTION]
- Users Impacted: [NUMBER/SCOPE]

## Rollback Actions Taken
- [ ] Plugin rollback executed
- [ ] Tier rollback executed
- [ ] Emergency rollback executed
- [ ] Database restored
- [ ] Configuration restored

## Validation Results
- [ ] System functionality restored
- [ ] Performance acceptable
- [ ] Data integrity confirmed
- [ ] Tests passing

## Root Cause Analysis
[Analysis of what caused the issue]

## Corrective Actions
[Steps to prevent recurrence]

## Lessons Learned
[Key takeaways for future migrations]
```

### Recovery Metrics Tracking

#### Key Metrics to Track
- **Recovery Time Objective (RTO):** Maximum acceptable downtime
- **Recovery Point Objective (RPO):** Maximum acceptable data loss
- **Mean Time to Recovery (MTTR):** Average time to complete rollback
- **Success Rate:** Percentage of successful rollbacks

#### Metrics Collection
```bash
# Track rollback timing
echo "Rollback started: $(date)" >> rollback-metrics.log
# ... rollback process ...
echo "Rollback completed: $(date)" >> rollback-metrics.log

# Calculate duration
start_time=$(grep "started" rollback-metrics.log | tail -1 | cut -d: -f2-)
end_time=$(grep "completed" rollback-metrics.log | tail -1 | cut -d: -f2-)
```

## Continuous Improvement

### Rollback Process Optimization

#### Regular Testing
- Monthly rollback drills
- Automated rollback testing
- Performance benchmarking
- Process refinement

#### Documentation Updates
- Update procedures based on experience
- Incorporate lessons learned
- Maintain current contact information
- Review and update scripts

#### Training and Preparedness
- Team training on rollback procedures
- Emergency contact lists
- Escalation procedures
- Communication protocols

## Notes

- Always test rollback procedures in non-production environments first
- Maintain multiple backup strategies (git, database, file system)
- Document all rollback actions for post-incident analysis
- Regularly validate backup integrity
- Keep rollback scripts updated and tested
- Ensure team members are trained on emergency procedures

This comprehensive rollback and recovery framework ensures that any issues encountered during the FilamentPHP v4 migration can be quickly and safely resolved, minimizing downtime and protecting data integrity.
