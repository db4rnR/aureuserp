# Migration Completeness Validation Report

Generated: 2025-06-20 15:55:34

## Summary

- **Total files validated:** 2703
- **Valid files:** 2574
- **Files with issues:** 129
- **Files with warnings:** 204
- **Total issues:** 507
- **Total warnings:** 428

**Migration Completeness:** 95.23%

## Issue Breakdown

- **Namespace Issues:** 186
- **Import Issues:** 301
- **Method Signature Issues:** 9
- **Method Call Issues:** 11

## Warning Breakdown

- **Import Warnings:** 274
- **Component Warnings:** 64
- **Signature Warnings:** 90

## Files with Issues

### OrderResource.php
Path: `plugins/webkul/purchases/src/Filament/Admin/Clusters/Orders/Resources/OrderResource.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File has infolist method but missing Infolist import

### OrderResource.php
Path: `plugins/webkul/purchases/src/Filament/Customer/Clusters/Account/Resources/OrderResource.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

### AttributeResource.php
Path: `plugins/webkul/products/src/Filament/Resources/AttributeResource.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File has infolist method but missing Infolist import

### TeamResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Configuration/Resources/TeamResource.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File has infolist method but missing Infolist import

### QuotationResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Orders/Resources/QuotationResource.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File has infolist method but missing Infolist import

### Products.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Settings/Pages/Products.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ProductResource.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Vendors/Resources/ProductResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File contains mixed old and new import patterns

### BankAccountsRelationManager.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Vendors/Resources/VendorResource/RelationManagers/BankAccountsRelationManager.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

### ManageBankAccounts.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Vendors/Resources/VendorResource/Pages/ManageBankAccounts.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

### VendorResource.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Vendors/Resources/VendorResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### ManageBankAccounts.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Customer/Resources/PartnerResource/Pages/ManageBankAccounts.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

### AcceptInvitation.php
Path: `plugins/webkul/security/src/Livewire/AcceptInvitation.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### RoleResource.php
Path: `plugins/webkul/security/src/Filament/Resources/RoleResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Tabs;
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old method signature pattern: /public\s+static\s+function\s+form\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ListUsers.php
Path: `plugins/webkul/security/src/Filament/Resources/UserResource/Pages/ListUsers.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### UserResource.php
Path: `plugins/webkul/security/src/Filament/Resources/UserResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old method signature pattern: /public\s+static\s+function\s+form\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i
- Found old method signature pattern: /public\s+static\s+function\s+infolist\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature
- File contains mixed old and new import patterns

### CompanyResource.php
Path: `plugins/webkul/security/src/Filament/Resources/CompanyResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old method signature pattern: /public\s+static\s+function\s+form\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i
- Found old method signature pattern: /public\s+static\s+function\s+infolist\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- Infolist method may not have correct v4 signature
- File contains mixed old and new import patterns

### TeamResource.php
Path: `plugins/webkul/security/src/Filament/Resources/TeamResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old method signature pattern: /public\s+static\s+function\s+form\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i
- Found old method signature pattern: /public\s+static\s+function\s+infolist\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature
- File contains mixed old and new import patterns

### BranchesRelationManager.php
Path: `plugins/webkul/security/src/Filament/Resources/CompanyResource/RelationManagers/BranchesRelationManager.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Tabs;
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature
- File contains mixed old and new import patterns

### ManageUsers.php
Path: `plugins/webkul/security/src/Filament/Clusters/Settings/Pages/ManageUsers.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ManageActivity.php
Path: `plugins/webkul/security/src/Filament/Clusters/Settings/Pages/ManageActivity.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### LeaveAccrualPlan.php
Path: `plugins/webkul/time-off/src/Traits/LeaveAccrualPlan.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature
- File contains mixed old and new import patterns

### PublicHolidayResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Configurations/Resources/PublicHolidayResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### LeaveTypeResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Configurations/Resources/LeaveTypeResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### AccrualPlanResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Configurations/Resources/AccrualPlanResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### ListLeaveTypes.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Configurations/Resources/LeaveTypeResource/Pages/ListLeaveTypes.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### MandatoryDayResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Configurations/Resources/MandatoryDayResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### MyTimeOffResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/MyTime/Resources/MyTimeOffResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### MyAllocationResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/MyTime/Resources/MyAllocationResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### ByEmployeeResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Reporting/Resources/ByEmployeeResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import

### TimeOffResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Management/Resources/TimeOffResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### AllocationResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Management/Resources/AllocationResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### CalendarWidget.php
Path: `plugins/webkul/time-off/src/Filament/Widgets/CalendarWidget.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature
- File uses Form components but has no form method
- File has infolist method but uses form components without form method
- File contains mixed old and new import patterns

### OverviewCalendarWidget.php
Path: `plugins/webkul/time-off/src/Filament/Widgets/OverviewCalendarWidget.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature
- File uses Form components but has no form method
- File has infolist method but uses form components without form method
- File contains mixed old and new import patterns

### TaskResource.php
Path: `plugins/webkul/projects/src/Filament/Resources/TaskResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### MilestonesRelationManager.php
Path: `plugins/webkul/projects/src/Filament/Resources/ProjectResource/RelationManagers/MilestonesRelationManager.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

### TaskStagesRelationManager.php
Path: `plugins/webkul/projects/src/Filament/Resources/ProjectResource/RelationManagers/TaskStagesRelationManager.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

### ManageTasks.php
Path: `plugins/webkul/projects/src/Filament/Resources/ProjectResource/Pages/ManageTasks.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ManageMilestones.php
Path: `plugins/webkul/projects/src/Filament/Resources/ProjectResource/Pages/ManageMilestones.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

### ProjectResource.php
Path: `plugins/webkul/projects/src/Filament/Resources/ProjectResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### TimesheetsRelationManager.php
Path: `plugins/webkul/projects/src/Filament/Resources/TaskResource/RelationManagers/TimesheetsRelationManager.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### SubTasksRelationManager.php
Path: `plugins/webkul/projects/src/Filament/Resources/TaskResource/RelationManagers/SubTasksRelationManager.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ManageTimesheets.php
Path: `plugins/webkul/projects/src/Filament/Resources/TaskResource/Pages/ManageTimesheets.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ManageSubTasks.php
Path: `plugins/webkul/projects/src/Filament/Resources/TaskResource/Pages/ManageSubTasks.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ManageTaskStages.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/TaskStageResource/Pages/ManageTaskStages.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### TaskStageResource.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/TaskStageResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File contains mixed old and new import patterns

### ActivityPlanResource.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/ActivityPlanResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### TagResource.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/TagResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File contains mixed old and new import patterns

### ProjectStageResource.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/ProjectStageResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File contains mixed old and new import patterns

### MilestoneResource.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/MilestoneResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File contains mixed old and new import patterns

### ManageTags.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/TagResource/Pages/ManageTags.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### ActivityTemplateRelationManager.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/ActivityPlanResource/RelationManagers/ActivityTemplateRelationManager.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ListActivityPlans.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/ActivityPlanResource/Pages/ListActivityPlans.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### ManageProjectStages.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/ProjectStageResource/Pages/ManageProjectStages.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### ManageTasks.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Settings/Pages/ManageTasks.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ManageTime.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Settings/Pages/ManageTime.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### Dashboard.php
Path: `plugins/webkul/projects/src/Filament/Pages/Dashboard.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### CandidateSkillRelation.php
Path: `plugins/webkul/recruitments/src/Traits/CandidateSkillRelation.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature
- File contains mixed old and new import patterns

### StageResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/StageResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### UTMSourceResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/UTMSourceResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### RefuseReasonResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/RefuseReasonResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### UTMMediumResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/UTMMediumResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### JobPositionResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/JobPositionResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### ApplicantCategoryResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/ApplicantCategoryResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### DegreeResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/DegreeResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### JobByPositionResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/JobByPositionResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import

### ListApplicants.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/ApplicantResource/Pages/ListApplicants.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### ViewApplicant.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/ApplicantResource/Pages/ViewApplicant.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### EditApplicant.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/ApplicantResource/Pages/EditApplicant.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### ApplicantResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/ApplicantResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### CandidateResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/CandidateResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### Recruitments.php
Path: `plugins/webkul/recruitments/src/Filament/Pages/Recruitments.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### PageResource.php
Path: `plugins/webkul/website/src/Filament/Admin/Resources/PageResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### ManageContacts.php
Path: `plugins/webkul/website/src/Filament/Admin/Clusters/Settings/Pages/ManageContacts.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### Login.php
Path: `plugins/webkul/website/src/Filament/Customer/Auth/Login.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### Register.php
Path: `plugins/webkul/website/src/Filament/Customer/Auth/Register.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### RequestPasswordReset.php
Path: `plugins/webkul/website/src/Filament/Customer/Auth/PasswordReset/RequestPasswordReset.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ResetPassword.php
Path: `plugins/webkul/website/src/Filament/Customer/Auth/PasswordReset/ResetPassword.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ChatterPanel.php
Path: `plugins/webkul/chatter/src/Livewire/ChatterPanel.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File uses Form components but has no form method
- File uses Infolist components but has no infolist method
- File contains mixed old and new import patterns

### FollowerAction.php
Path: `plugins/webkul/chatter/src/Filament/Actions/Chatter/FollowerAction.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### LogAction.php
Path: `plugins/webkul/chatter/src/Filament/Actions/Chatter/LogAction.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### MessageAction.php
Path: `plugins/webkul/chatter/src/Filament/Actions/Chatter/MessageAction.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### ActivityAction.php
Path: `plugins/webkul/chatter/src/Filament/Actions/Chatter/ActivityAction.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### ListPaymentTerms.php
Path: `plugins/webkul/accounts/src/Filament/Resources/PaymentTermResource/Pages/ListPaymentTerms.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### ActivityTypeResource.php
Path: `plugins/webkul/support/src/Filament/Resources/ActivityTypeResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old method signature pattern: /public\s+static\s+function\s+form\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i
- Found old method signature pattern: /public\s+static\s+function\s+infolist\s*\(\s*Schema\s+\$schema\s*\)\s*:\s*Schema/i
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature
- File contains mixed old and new import patterns

### ListActivityTypes.php
Path: `plugins/webkul/support/src/Filament/Resources/ActivityTypeResource/Pages/ListActivityTypes.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### FieldResource.php
Path: `plugins/webkul/fields/src/Filament/Resources/FieldResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ListFields.php
Path: `plugins/webkul/fields/src/Filament/Resources/FieldResource/Pages/ListFields.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### PresetView.php
Path: `plugins/webkul/table-views/src/Filament/Components/PresetView.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### RulesRelationManager.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RouteResource/RelationManagers/RulesRelationManager.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

### ManageRules.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RouteResource/Pages/ManageRules.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

### ListRoutes.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RouteResource/Pages/ListRoutes.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### ManageRoutes.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/WarehouseResource/Pages/ManageRoutes.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

### ListWarehouses.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/WarehouseResource/Pages/ListWarehouses.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### ListLocations.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/LocationResource/Pages/ListLocations.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### WarehouseResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/WarehouseResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### RouteResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RouteResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### PackageTypeResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/PackageTypeResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### CapacityByPackagesRelationManager.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource/RelationManagers/CapacityByPackagesRelationManager.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### CapacityByProductsRelationManager.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource/RelationManagers/CapacityByProductsRelationManager.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ManageLocations.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource/Pages/ManageLocations.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

### ManageCapacityByProducts.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource/Pages/ManageCapacityByProducts.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ManageCapacityByPackages.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource/Pages/ManageCapacityByPackages.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### OperationTypeResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/OperationTypeResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Tabs;
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### RuleResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RuleResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### LocationResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/LocationResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### ListRules.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RuleResource/Pages/ListRules.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### PackagingResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/PackagingResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### StorageCategoryResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### ProductCategoryResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/ProductCategoryResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### ListOperationTypes.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/OperationTypeResource/Pages/ListOperationTypes.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old namespace pattern: Filament\Schemas\Components\

### ManageLogistics.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Settings/Pages/ManageLogistics.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ManageOperations.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Settings/Pages/ManageOperations.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ManageTraceability.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Settings/Pages/ManageTraceability.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ManageProducts.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Settings/Pages/ManageProducts.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ManageWarehouses.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Settings/Pages/ManageWarehouses.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### ProductResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Products/Resources/ProductResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Fieldset;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### PackageResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Products/Resources/PackageResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### EditProduct.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Products/Resources/ProductResource/Pages/EditProduct.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### ManageQuantities.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Products/Resources/ProductResource/Pages/ManageQuantities.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature
- File contains mixed old and new import patterns

### LotResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Products/Resources/LotResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### ReceiptResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/ReceiptResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import

### ScrapResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/ScrapResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### InternalResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/InternalResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import

### DeliveryResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/DeliveryResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import

### QuantityResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/QuantityResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File contains mixed old and new import patterns

### OperationResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/OperationResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old import statement: use Filament\Schemas\Components\Section;
- Found old import statement: use Filament\Schemas\Components\Grid;
- Found old import statement: use Filament\Schemas\Components\Tabs;
- Found old import statement: use Filament\Schemas\Components\Tabs\Tab;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import
- File contains mixed old and new import patterns

### ReplenishmentResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/ReplenishmentResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import

### DropshipResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/DropshipResource.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- File has infolist method but missing Infolist import

### LabelsAction.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Actions/Print/LabelsAction.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Components\Group;
- Found old import statement: use Filament\Schemas\Components\Utilities\Get;
- Found old import statement: use Filament\Schemas\Components\Utilities\Set;
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

## Files with Warnings Only

### PostResource.php
Path: `plugins/webkul/blogs/src/Filament/Admin/Resources/PostResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ManageTags.php
Path: `plugins/webkul/blogs/src/Filament/Admin/Clusters/Configurations/Resources/TagResource/Pages/ManageTags.php`

**Warnings:**
- File uses Form components but has no form method

### VendorPriceResource.php
Path: `plugins/webkul/purchases/src/Filament/Admin/Clusters/Configurations/Resources/VendorPriceResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ManageOrders.php
Path: `plugins/webkul/purchases/src/Filament/Admin/Clusters/Settings/Pages/ManageOrders.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageProducts.php
Path: `plugins/webkul/purchases/src/Filament/Admin/Clusters/Settings/Pages/ManageProducts.php`

**Warnings:**
- Form method may not have correct v4 signature

### ProductResource.php
Path: `plugins/webkul/purchases/src/Filament/Admin/Clusters/Products/Resources/ProductResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ManageVendors.php
Path: `plugins/webkul/purchases/src/Filament/Admin/Clusters/Products/Resources/ProductResource/Pages/ManageVendors.php`

**Warnings:**
- Form method may not have correct v4 signature

### SendPOEmailAction.php
Path: `plugins/webkul/purchases/src/Filament/Admin/Clusters/Orders/Resources/OrderResource/Actions/SendPOEmailAction.php`

**Warnings:**
- File uses Form components but has no form method

### SendEmailAction.php
Path: `plugins/webkul/purchases/src/Filament/Admin/Clusters/Orders/Resources/OrderResource/Actions/SendEmailAction.php`

**Warnings:**
- File uses Form components but has no form method

### PurchaseAgreementResource.php
Path: `plugins/webkul/purchases/src/Filament/Admin/Clusters/Orders/Resources/PurchaseAgreementResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ProductResource.php
Path: `plugins/webkul/products/src/Filament/Resources/ProductResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### EditProduct.php
Path: `plugins/webkul/products/src/Filament/Resources/ProductResource/Pages/EditProduct.php`

**Warnings:**
- File uses Form components but has no form method

### ViewProduct.php
Path: `plugins/webkul/products/src/Filament/Resources/ProductResource/Pages/ViewProduct.php`

**Warnings:**
- File uses Form components but has no form method

### ManageVariants.php
Path: `plugins/webkul/products/src/Filament/Resources/ProductResource/Pages/ManageVariants.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ManageAttributes.php
Path: `plugins/webkul/products/src/Filament/Resources/ProductResource/Pages/ManageAttributes.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageProducts.php
Path: `plugins/webkul/products/src/Filament/Resources/CategoryResource/Pages/ManageProducts.php`

**Warnings:**
- Form method may not have correct v4 signature

### CategoryResource.php
Path: `plugins/webkul/products/src/Filament/Resources/CategoryResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### PackagingResource.php
Path: `plugins/webkul/products/src/Filament/Resources/PackagingResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ListAttributes.php
Path: `plugins/webkul/products/src/Filament/Resources/AttributeResource/Pages/ListAttributes.php`

**Warnings:**
- File uses Form components but has no form method

### ManagePricing.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Settings/Pages/ManagePricing.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageQuotationAndOrder.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Settings/Pages/ManageQuotationAndOrder.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageProducts.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Settings/Pages/ManageProducts.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageInvoice.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Settings/Pages/ManageInvoice.php`

**Warnings:**
- Form method may not have correct v4 signature

### OrderTemplateProductResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Configuration/Resources/OrderTemplateProductResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ActivityPlanResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Configuration/Resources/ActivityPlanResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### QuotationTemplateResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Configuration/Resources/QuotationTemplateResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### TagResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Configuration/Resources/TagResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ListTeams.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Configuration/Resources/TeamResource/Pages/ListTeams.php`

**Warnings:**
- File uses Form components but has no form method

### ActivityTemplateRelationManager.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Configuration/Resources/ActivityPlanResource/RelationManagers/ActivityTemplateRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ListActivityPlans.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Configuration/Resources/ActivityPlanResource/Pages/ListActivityPlans.php`

**Warnings:**
- File uses Form components but has no form method

### OrderResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Orders/Resources/OrderResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### CreateInvoiceAction.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Orders/Resources/QuotationResource/Actions/CreateInvoiceAction.php`

**Warnings:**
- File uses Form components but has no form method

### CancelQuotationAction.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Orders/Resources/QuotationResource/Actions/CancelQuotationAction.php`

**Warnings:**
- File uses Form components but has no form method

### SendByEmailAction.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Orders/Resources/QuotationResource/Actions/SendByEmailAction.php`

**Warnings:**
- File uses Form components but has no form method

### OrderToInvoiceResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/ToInvoice/Resources/OrderToInvoiceResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### OrderToUpsellResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/ToInvoice/Resources/OrderToUpsellResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### EditUser.php
Path: `plugins/webkul/security/src/Filament/Resources/UserResource/Pages/EditUser.php`

**Warnings:**
- File uses Form components but has no form method

### HolidayAction.php
Path: `plugins/webkul/time-off/src/Filament/Actions/HolidayAction.php`

**Warnings:**
- File uses Form components but has no form method

### TitleTextEntry.php
Path: `plugins/webkul/chatter/src/Filament/Infolists/Components/Messages/TitleTextEntry.php`

**Warnings:**
- File uses Form components but has no form method
- File uses Infolist components but has no infolist method

### ContentTextEntry.php
Path: `plugins/webkul/chatter/src/Filament/Infolists/Components/Messages/ContentTextEntry.php`

**Warnings:**
- File uses Form components but has no form method
- File uses Infolist components but has no infolist method

### MessageRepeatableEntry.php
Path: `plugins/webkul/chatter/src/Filament/Infolists/Components/Messages/MessageRepeatableEntry.php`

**Warnings:**
- File uses Infolist components but has no infolist method

### ActivitiesRepeatableEntry.php
Path: `plugins/webkul/chatter/src/Filament/Infolists/Components/Activities/ActivitiesRepeatableEntry.php`

**Warnings:**
- File uses Infolist components but has no infolist method

### TitleTextEntry.php
Path: `plugins/webkul/chatter/src/Filament/Infolists/Components/Activities/TitleTextEntry.php`

**Warnings:**
- File uses Form components but has no form method
- File uses Infolist components but has no infolist method

### ContentTextEntry.php
Path: `plugins/webkul/chatter/src/Filament/Infolists/Components/Activities/ContentTextEntry.php`

**Warnings:**
- File uses Form components but has no form method
- File uses Infolist components but has no infolist method

### FileAction.php
Path: `plugins/webkul/chatter/src/Filament/Actions/Chatter/FileAction.php`

**Warnings:**
- File uses Form components but has no form method

### FiscalPositionTax.php
Path: `plugins/webkul/accounts/src/Traits/FiscalPositionTax.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### TaxPartition.php
Path: `plugins/webkul/accounts/src/Traits/TaxPartition.php`

**Warnings:**
- Form method may not have correct v4 signature

### PaymentDueTerm.php
Path: `plugins/webkul/accounts/src/Traits/PaymentDueTerm.php`

**Warnings:**
- Form method may not have correct v4 signature

### PayAction.php
Path: `plugins/webkul/accounts/src/Filament/Resources/InvoiceResource/Actions/PayAction.php`

**Warnings:**
- File uses Form components but has no form method

### CreditNoteAction.php
Path: `plugins/webkul/accounts/src/Filament/Resources/InvoiceResource/Actions/CreditNoteAction.php`

**Warnings:**
- File uses Form components but has no form method

### PrintAndSendAction.php
Path: `plugins/webkul/accounts/src/Filament/Resources/InvoiceResource/Actions/PrintAndSendAction.php`

**Warnings:**
- File uses Form components but has no form method

### CreditNoteAction.php
Path: `plugins/webkul/accounts/src/Filament/Resources/BillResource/Actions/CreditNoteAction.php`

**Warnings:**
- File uses Form components but has no form method

### progress-bar-entry.blade.php
Path: `plugins/webkul/support/resources/views/tables/infolists/progress-bar-entry.blade.php`

**Warnings:**
- File uses Infolist components but has no infolist method

### ProgressBarEntry.php
Path: `plugins/webkul/support/src/Filament/Tables/Infolists/ProgressBarEntry.php`

**Warnings:**
- File uses Infolist components but has no infolist method

### ManageIndustries.php
Path: `plugins/webkul/partners/src/Filament/Resources/IndustryResource/Pages/ManageIndustries.php`

**Warnings:**
- File uses Form components but has no form method

### ManageBanks.php
Path: `plugins/webkul/partners/src/Filament/Resources/BankResource/Pages/ManageBanks.php`

**Warnings:**
- File uses Form components but has no form method

### PartnerResource.php
Path: `plugins/webkul/partners/src/Filament/Resources/PartnerResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ManageBankAccounts.php
Path: `plugins/webkul/partners/src/Filament/Resources/BankAccountResource/Pages/ManageBankAccounts.php`

**Warnings:**
- File uses Form components but has no form method

### ManageTags.php
Path: `plugins/webkul/partners/src/Filament/Resources/TagResource/Pages/ManageTags.php`

**Warnings:**
- File uses Form components but has no form method

### AddressesRelationManager.php
Path: `plugins/webkul/partners/src/Filament/Resources/PartnerResource/RelationManagers/AddressesRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature

### ContactsRelationManager.php
Path: `plugins/webkul/partners/src/Filament/Resources/PartnerResource/RelationManagers/ContactsRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageAddresses.php
Path: `plugins/webkul/partners/src/Filament/Resources/PartnerResource/Pages/ManageAddresses.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageContacts.php
Path: `plugins/webkul/partners/src/Filament/Resources/PartnerResource/Pages/ManageContacts.php`

**Warnings:**
- Form method may not have correct v4 signature

### CustomFields.php
Path: `plugins/webkul/fields/src/Filament/Forms/Components/CustomFields.php`

**Warnings:**
- File uses Form components but has no form method

### ProgressStepper.php
Path: `plugins/webkul/fields/src/Filament/Forms/Components/ProgressStepper.php`

**Warnings:**
- File uses Form components but has no form method

### TimeToFloatPicker.php
Path: `plugins/webkul/fields/src/Filament/Forms/Components/TimeToFloatPicker.php`

**Warnings:**
- File uses Form components but has no form method

### HasCustomFields.php
Path: `plugins/webkul/fields/src/Filament/Traits/HasCustomFields.php`

**Warnings:**
- File uses Form components but has no form method
- File uses Infolist components but has no infolist method

### CustomEntries.php
Path: `plugins/webkul/fields/src/Filament/Infolists/Components/CustomEntries.php`

**Warnings:**
- File uses Infolist components but has no infolist method

### EditViewAction.php
Path: `plugins/webkul/table-views/src/Filament/Actions/EditViewAction.php`

**Warnings:**
- File uses Form components but has no form method

### CreateViewAction.php
Path: `plugins/webkul/table-views/src/Filament/Actions/CreateViewAction.php`

**Warnings:**
- File uses Form components but has no form method

### EmployeeSkillRelation.php
Path: `plugins/webkul/employees/src/Traits/Resources/Employee/EmployeeSkillRelation.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### EmployeeResumeRelation.php
Path: `plugins/webkul/employees/src/Traits/Resources/Employee/EmployeeResumeRelation.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### EmployeeResource.php
Path: `plugins/webkul/employees/src/Filament/Resources/EmployeeResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### DepartmentResource.php
Path: `plugins/webkul/employees/src/Filament/Resources/DepartmentResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### CalendarAttendance.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/CalendarResource/RelationManagers/CalendarAttendance.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ListCalendars.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/CalendarResource/Pages/ListCalendars.php`

**Warnings:**
- File uses Form components but has no form method

### SkillTypeResource.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/SkillTypeResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ListWorkLocations.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/WorkLocationResource/Pages/ListWorkLocations.php`

**Warnings:**
- File uses Form components but has no form method

### ActivityPlanResource.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/ActivityPlanResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### JobPositionResource.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/JobPositionResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### CalendarResource.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/CalendarResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### DepartureReasonResource.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/DepartureReasonResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### EmployeeCategoryResource.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/EmployeeCategoryResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### WorkLocationResource.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/WorkLocationResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### SkillsRelationManager.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/SkillTypeResource/RelationManagers/SkillsRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### SkillLevelRelationManager.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/SkillTypeResource/RelationManagers/SkillLevelRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ListSkillTypes.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/SkillTypeResource/Pages/ListSkillTypes.php`

**Warnings:**
- File uses Form components but has no form method

### EmploymentTypeResource.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/EmploymentTypeResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ActivityTemplateRelationManager.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/ActivityPlanResource/RelationManagers/ActivityTemplateRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ListActivityPlans.php
Path: `plugins/webkul/employees/src/Filament/Clusters/Configurations/Resources/ActivityPlanResource/Pages/ListActivityPlans.php`

**Warnings:**
- File uses Form components but has no form method

