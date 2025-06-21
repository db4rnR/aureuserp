# Migration Completeness Validation Report

Generated: 2025-06-20 23:57:47

## Summary

- **Total files validated:** 2703
- **Valid files:** 2684
- **Files with issues:** 19
- **Files with warnings:** 208
- **Total issues:** 29
- **Total warnings:** 252

**Migration Completeness:** 99.3%

## Issue Breakdown

- **Namespace Issues:** 18
- **Method Call Issues:** 6
- **Import Issues:** 5

## Warning Breakdown

- **Import Warnings:** 93
- **Component Warnings:** 78
- **Signature Warnings:** 81

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

### QuotationResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Orders/Resources/QuotationResource.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File has infolist method but missing Infolist import

### RoleResource.php
Path: `plugins/webkul/security/src/Filament/Resources/RoleResource.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

### BranchesRelationManager.php
Path: `plugins/webkul/security/src/Filament/Resources/CompanyResource/RelationManagers/BranchesRelationManager.php`

**Issues:**
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ManageUsers.php
Path: `plugins/webkul/security/src/Filament/Clusters/Settings/Pages/ManageUsers.php`

**Issues:**
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i

**Warnings:**
- Form method may not have correct v4 signature

### ManageActivity.php
Path: `plugins/webkul/security/src/Filament/Clusters/Settings/Pages/ManageActivity.php`

**Issues:**
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- Form method may not have correct v4 signature

### Dashboard.php
Path: `plugins/webkul/projects/src/Filament/Pages/Dashboard.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### ListApplicants.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/ApplicantResource/Pages/ListApplicants.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### ApplicantResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/ApplicantResource.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File has infolist method but missing Infolist import

### Recruitments.php
Path: `plugins/webkul/recruitments/src/Filament/Pages/Recruitments.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### Login.php
Path: `plugins/webkul/website/src/Filament/Customer/Auth/Login.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- Form method may not have correct v4 signature

### Register.php
Path: `plugins/webkul/website/src/Filament/Customer/Auth/Register.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- Form method may not have correct v4 signature

### RequestPasswordReset.php
Path: `plugins/webkul/website/src/Filament/Customer/Auth/PasswordReset/RequestPasswordReset.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- Form method may not have correct v4 signature

### ResetPassword.php
Path: `plugins/webkul/website/src/Filament/Customer/Auth/PasswordReset/ResetPassword.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- Form method may not have correct v4 signature

### FollowerAction.php
Path: `plugins/webkul/chatter/src/Filament/Actions/Chatter/FollowerAction.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old method call pattern: /\$schema\s*->\s*components\s*\(/i
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### LogAction.php
Path: `plugins/webkul/chatter/src/Filament/Actions/Chatter/LogAction.php`

**Issues:**
- Found old import statement: use Filament\Schemas\Schema;
- Found old namespace pattern: Filament\Schemas\Components\
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File uses Form components but has no form method
- File contains mixed old and new import patterns

### MessageAction.php
Path: `plugins/webkul/chatter/src/Filament/Actions/Chatter/MessageAction.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Components\

**Warnings:**
- File uses Form components but has no form method

### FieldResource.php
Path: `plugins/webkul/fields/src/Filament/Resources/FieldResource.php`

**Issues:**
- Found old namespace pattern: Filament\Schemas\Schema

**Warnings:**
- File has form method but missing Form import
- Form method may not have correct v4 signature

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

### AttributeResource.php
Path: `plugins/webkul/products/src/Filament/Resources/AttributeResource.php`

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

### TeamResource.php
Path: `plugins/webkul/sales/src/Filament/Clusters/Configuration/Resources/TeamResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

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

### Products.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Settings/Pages/Products.php`

**Warnings:**
- Form method may not have correct v4 signature

### BankAccountsRelationManager.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Vendors/Resources/VendorResource/RelationManagers/BankAccountsRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageBankAccounts.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Vendors/Resources/VendorResource/Pages/ManageBankAccounts.php`

**Warnings:**
- Form method may not have correct v4 signature

### VendorResource.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Vendors/Resources/VendorResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ManageBankAccounts.php
Path: `plugins/webkul/invoices/src/Filament/Clusters/Customer/Resources/PartnerResource/Pages/ManageBankAccounts.php`

**Warnings:**
- Form method may not have correct v4 signature

### AcceptInvitation.php
Path: `plugins/webkul/security/src/Livewire/AcceptInvitation.php`

**Warnings:**
- Form method may not have correct v4 signature

### ListUsers.php
Path: `plugins/webkul/security/src/Filament/Resources/UserResource/Pages/ListUsers.php`

**Warnings:**
- File uses Form components but has no form method

### EditUser.php
Path: `plugins/webkul/security/src/Filament/Resources/UserResource/Pages/EditUser.php`

**Warnings:**
- File uses Form components but has no form method

### UserResource.php
Path: `plugins/webkul/security/src/Filament/Resources/UserResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### TeamResource.php
Path: `plugins/webkul/security/src/Filament/Resources/TeamResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### LeaveAccrualPlan.php
Path: `plugins/webkul/time-off/src/Traits/LeaveAccrualPlan.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### PublicHolidayResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Configurations/Resources/PublicHolidayResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### LeaveTypeResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Configurations/Resources/LeaveTypeResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### AccrualPlanResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Configurations/Resources/AccrualPlanResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ListLeaveTypes.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Configurations/Resources/LeaveTypeResource/Pages/ListLeaveTypes.php`

**Warnings:**
- File uses Form components but has no form method

### MandatoryDayResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Configurations/Resources/MandatoryDayResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### MyTimeOffResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/MyTime/Resources/MyTimeOffResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### MyAllocationResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/MyTime/Resources/MyAllocationResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### TimeOffResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Management/Resources/TimeOffResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### AllocationResource.php
Path: `plugins/webkul/time-off/src/Filament/Clusters/Management/Resources/AllocationResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### HolidayAction.php
Path: `plugins/webkul/time-off/src/Filament/Actions/HolidayAction.php`

**Warnings:**
- File uses Form components but has no form method

### CalendarWidget.php
Path: `plugins/webkul/time-off/src/Filament/Widgets/CalendarWidget.php`

**Warnings:**
- Infolist method may not have correct v4 signature
- File uses Form components but has no form method
- File has infolist method but uses form components without form method

### OverviewCalendarWidget.php
Path: `plugins/webkul/time-off/src/Filament/Widgets/OverviewCalendarWidget.php`

**Warnings:**
- Infolist method may not have correct v4 signature
- File uses Form components but has no form method
- File has infolist method but uses form components without form method

### TaskResource.php
Path: `plugins/webkul/projects/src/Filament/Resources/TaskResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### MilestonesRelationManager.php
Path: `plugins/webkul/projects/src/Filament/Resources/ProjectResource/RelationManagers/MilestonesRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature

### TaskStagesRelationManager.php
Path: `plugins/webkul/projects/src/Filament/Resources/ProjectResource/RelationManagers/TaskStagesRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageTasks.php
Path: `plugins/webkul/projects/src/Filament/Resources/ProjectResource/Pages/ManageTasks.php`

**Warnings:**
- Infolist method may not have correct v4 signature

### ManageMilestones.php
Path: `plugins/webkul/projects/src/Filament/Resources/ProjectResource/Pages/ManageMilestones.php`

**Warnings:**
- Form method may not have correct v4 signature

### ProjectResource.php
Path: `plugins/webkul/projects/src/Filament/Resources/ProjectResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### TimesheetsRelationManager.php
Path: `plugins/webkul/projects/src/Filament/Resources/TaskResource/RelationManagers/TimesheetsRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature

### SubTasksRelationManager.php
Path: `plugins/webkul/projects/src/Filament/Resources/TaskResource/RelationManagers/SubTasksRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ManageTimesheets.php
Path: `plugins/webkul/projects/src/Filament/Resources/TaskResource/Pages/ManageTimesheets.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageSubTasks.php
Path: `plugins/webkul/projects/src/Filament/Resources/TaskResource/Pages/ManageSubTasks.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### ManageTaskStages.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/TaskStageResource/Pages/ManageTaskStages.php`

**Warnings:**
- File uses Form components but has no form method

### ActivityPlanResource.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/ActivityPlanResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ManageTags.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/TagResource/Pages/ManageTags.php`

**Warnings:**
- File uses Form components but has no form method

### ActivityTemplateRelationManager.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/ActivityPlanResource/RelationManagers/ActivityTemplateRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature

### ListActivityPlans.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/ActivityPlanResource/Pages/ListActivityPlans.php`

**Warnings:**
- File uses Form components but has no form method

### ManageProjectStages.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Configurations/Resources/ProjectStageResource/Pages/ManageProjectStages.php`

**Warnings:**
- File uses Form components but has no form method

### ManageTasks.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Settings/Pages/ManageTasks.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageTime.php
Path: `plugins/webkul/projects/src/Filament/Clusters/Settings/Pages/ManageTime.php`

**Warnings:**
- Form method may not have correct v4 signature

### CandidateSkillRelation.php
Path: `plugins/webkul/recruitments/src/Traits/CandidateSkillRelation.php`

**Warnings:**
- Form method may not have correct v4 signature
- File has infolist method but missing Infolist import
- Infolist method may not have correct v4 signature

### StageResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/StageResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### UTMSourceResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/UTMSourceResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### RefuseReasonResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/RefuseReasonResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### UTMMediumResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/UTMMediumResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### JobPositionResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/JobPositionResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ApplicantCategoryResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/ApplicantCategoryResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### DegreeResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Configurations/Resources/DegreeResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### JobByPositionResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/JobByPositionResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ViewApplicant.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/ApplicantResource/Pages/ViewApplicant.php`

**Warnings:**
- File uses Form components but has no form method

### EditApplicant.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/ApplicantResource/Pages/EditApplicant.php`

**Warnings:**
- File uses Form components but has no form method

### CandidateResource.php
Path: `plugins/webkul/recruitments/src/Filament/Clusters/Applications/Resources/CandidateResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### PageResource.php
Path: `plugins/webkul/website/src/Filament/Admin/Resources/PageResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ManageContacts.php
Path: `plugins/webkul/website/src/Filament/Admin/Clusters/Settings/Pages/ManageContacts.php`

**Warnings:**
- Form method may not have correct v4 signature

### ChatterPanel.php
Path: `plugins/webkul/chatter/src/Livewire/ChatterPanel.php`

**Warnings:**
- File uses Form components but has no form method
- File uses Infolist components but has no infolist method

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

### ActivityAction.php
Path: `plugins/webkul/chatter/src/Filament/Actions/Chatter/ActivityAction.php`

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

### ListPaymentTerms.php
Path: `plugins/webkul/accounts/src/Filament/Resources/PaymentTermResource/Pages/ListPaymentTerms.php`

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

### ActivityTypeResource.php
Path: `plugins/webkul/support/src/Filament/Resources/ActivityTypeResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ListActivityTypes.php
Path: `plugins/webkul/support/src/Filament/Resources/ActivityTypeResource/Pages/ListActivityTypes.php`

**Warnings:**
- File uses Form components but has no form method

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

### ListFields.php
Path: `plugins/webkul/fields/src/Filament/Resources/FieldResource/Pages/ListFields.php`

**Warnings:**
- File uses Form components but has no form method

### CustomEntries.php
Path: `plugins/webkul/fields/src/Filament/Infolists/Components/CustomEntries.php`

**Warnings:**
- File uses Infolist components but has no infolist method

### PresetView.php
Path: `plugins/webkul/table-views/src/Filament/Components/PresetView.php`

**Warnings:**
- File uses Form components but has no form method

### EditViewAction.php
Path: `plugins/webkul/table-views/src/Filament/Actions/EditViewAction.php`

**Warnings:**
- File uses Form components but has no form method

### CreateViewAction.php
Path: `plugins/webkul/table-views/src/Filament/Actions/CreateViewAction.php`

**Warnings:**
- File uses Form components but has no form method

### RulesRelationManager.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RouteResource/RelationManagers/RulesRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageRules.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RouteResource/Pages/ManageRules.php`

**Warnings:**
- Form method may not have correct v4 signature

### ListRoutes.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RouteResource/Pages/ListRoutes.php`

**Warnings:**
- File uses Form components but has no form method

### ManageRoutes.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/WarehouseResource/Pages/ManageRoutes.php`

**Warnings:**
- Form method may not have correct v4 signature

### ListWarehouses.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/WarehouseResource/Pages/ListWarehouses.php`

**Warnings:**
- File uses Form components but has no form method

### ListLocations.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/LocationResource/Pages/ListLocations.php`

**Warnings:**
- File uses Form components but has no form method

### WarehouseResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/WarehouseResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### RouteResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RouteResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### PackageTypeResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/PackageTypeResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### CapacityByPackagesRelationManager.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource/RelationManagers/CapacityByPackagesRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature

### CapacityByProductsRelationManager.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource/RelationManagers/CapacityByProductsRelationManager.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageLocations.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource/Pages/ManageLocations.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageCapacityByProducts.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource/Pages/ManageCapacityByProducts.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageCapacityByPackages.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource/Pages/ManageCapacityByPackages.php`

**Warnings:**
- Form method may not have correct v4 signature

### OperationTypeResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/OperationTypeResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### RuleResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RuleResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### LocationResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/LocationResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ListRules.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/RuleResource/Pages/ListRules.php`

**Warnings:**
- File uses Form components but has no form method

### PackagingResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/PackagingResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### StorageCategoryResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/StorageCategoryResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ProductCategoryResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/ProductCategoryResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ListOperationTypes.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Configurations/Resources/OperationTypeResource/Pages/ListOperationTypes.php`

**Warnings:**
- File uses Form components but has no form method

### ManageLogistics.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Settings/Pages/ManageLogistics.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageOperations.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Settings/Pages/ManageOperations.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageTraceability.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Settings/Pages/ManageTraceability.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageProducts.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Settings/Pages/ManageProducts.php`

**Warnings:**
- Form method may not have correct v4 signature

### ManageWarehouses.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Settings/Pages/ManageWarehouses.php`

**Warnings:**
- Form method may not have correct v4 signature

### ProductResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Products/Resources/ProductResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### PackageResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Products/Resources/PackageResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### EditProduct.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Products/Resources/ProductResource/Pages/EditProduct.php`

**Warnings:**
- File uses Form components but has no form method

### ManageQuantities.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Products/Resources/ProductResource/Pages/ManageQuantities.php`

**Warnings:**
- Form method may not have correct v4 signature

### LotResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Products/Resources/LotResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ReceiptResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/ReceiptResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### ScrapResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/ScrapResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### InternalResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/InternalResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### DeliveryResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/DeliveryResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### OperationResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/OperationResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### DropshipResource.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Resources/DropshipResource.php`

**Warnings:**
- File has infolist method but missing Infolist import

### LabelsAction.php
Path: `plugins/webkul/inventories/src/Filament/Clusters/Operations/Actions/Print/LabelsAction.php`

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

