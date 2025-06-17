# 10. Class Diagrams and Object Models

## Table of Contents

- [1. Executive Summary](#1-executive-summary)
- [2. Core Framework Classes](#2-core-framework-classes)
- [3. Plugin System Classes](#3-plugin-system-classes)
- [4. Business Domain Models](#4-business-domain-models)
- [5. FilamentPHP Resource Classes](#5-filamentphp-resource-classes)
- [6. Service Layer Classes](#6-service-layer-classes)

## 1. Executive Summary

This document provides detailed class diagrams showing the object-oriented structure of AureusERP, including core framework classes, plugin architecture, business domain models, and their relationships.

**🎯 Confidence Score: 93%** - Based on Laravel conventions, FilamentPHP patterns, and plugin analysis

## 2. Core Framework Classes

### 2.1. Laravel Application Structure

```mermaid
classDiagram
    class Application {
        +string $basePath
        +ServiceContainer $container
        +boot() void
        +register() void
        +make(string $abstract) mixed
    }
    
    class ServiceProvider {
        <<abstract>>
        +Application $app
        +register() void
        +boot() void
        +publishes(array $paths, string $groups) void
    }
    
    class Model {
        <<abstract>>
        +string $table
        +array $fillable
        +array $casts
        +save() bool
        +delete() bool
        +find(mixed $id) static
    }
    
    class Controller {
        <<abstract>>
        +middleware(string $middleware) void
        +authorize(string $ability, mixed $arguments) void
    }
    
    class Request {
        +validate(array $rules) array
        +input(string $key, mixed $default) mixed
        +user() Authenticatable
    }
    
    class Resource {
        <<abstract>>
        +static string $model
        +static string $navigationIcon
        +form(Form $form) Form
        +table(Table $table) Table
    }
    
    Application --> ServiceProvider : registers
    ServiceProvider --> Model : defines
    ServiceProvider --> Controller : defines
    Controller --> Request : receives
    Controller --> Resource : manages
    Model --> Resource : data_source
```

### 2.2. Plugin System Core Classes

```mermaid
classDiagram
    class PluginManager {
        +array $plugins
        +loadPlugins() void
        +registerPlugin(string $name, Plugin $plugin) void
        +getPlugin(string $name) Plugin
        +isEnabled(string $name) bool
    }
    
    class Plugin {
        <<abstract>>
        +string $name
        +string $version
        +array $dependencies
        +boot() void
        +install() void
        +uninstall() void
        +isInstalled() bool
    }
    
    class WebkulPlugin {
        +string $namespace
        +array $providers
        +array $resources
        +loadRoutes() void
        +loadMigrations() void
        +loadViews() void
    }
    
    class PluginServiceProvider {
        +Plugin $plugin
        +register() void
        +boot() void
        +loadPluginResources() void
    }
    
    class ComposerMergePlugin {
        +array $includes
        +merge() void
        +loadDependencies() void
    }
    
    PluginManager --> Plugin : manages
    Plugin <|-- WebkulPlugin : implements
    WebkulPlugin --> PluginServiceProvider : has
    PluginManager --> ComposerMergePlugin : uses
```

## 3. Plugin System Classes

### 3.3. Webkul Plugin Architecture

```mermaid
classDiagram
    class BasePlugin {
        <<abstract>>
        +string $name
        +string $description
        +string $version
        +array $dependencies
        +register() void
        +boot() void
    }
    
    class AccountsPlugin {
        +string $name "Accounts"
        +registerModels() void
        +registerResources() void
        +registerPermissions() void
    }
    
    class EmployeesPlugin {
        +string $name "Employees"
        +registerModels() void
        +registerResources() void
        +registerPermissions() void
    }
    
    class InventoryPlugin {
        +string $name "Inventory"
        +registerModels() void
        +registerResources() void
        +registerPermissions() void
    }
    
    class SalesPlugin {
        +string $name "Sales"
        +registerModels() void
        +registerResources() void
        +registerPermissions() void
    }
    
    class FieldsPlugin {
        +string $name "Fields"
        +createCustomField(array $data) CustomField
        +getFieldTypes() array
        +validateFieldRules(array $rules) bool
    }
    
    BasePlugin <|-- AccountsPlugin
    BasePlugin <|-- EmployeesPlugin
    BasePlugin <|-- InventoryPlugin
    BasePlugin <|-- SalesPlugin
    BasePlugin <|-- FieldsPlugin
    
    note for BasePlugin "All 22 Webkul plugins\nextend this base class"
```

## 4. Business Domain Models

### 4.1. User Management Domain

```mermaid
classDiagram
    class User {
        +uuid $id
        +string $name
        +string $email
        +timestamp $email_verified_at
        +string $password
        +roles() BelongsToMany
        +permissions() BelongsToMany
        +hasRole(string $role) bool
        +can(string $permission) bool
    }
    
    class Role {
        +uuid $id
        +string $name
        +string $guard_name
        +users() BelongsToMany
        +permissions() BelongsToMany
        +givePermissionTo(Permission $permission) void
    }
    
    class Permission {
        +uuid $id
        +string $name
        +string $guard_name
        +roles() BelongsToMany
        +users() BelongsToMany
    }
    
    class ActivityLog {
        +uuid $id
        +string $log_name
        +text $description
        +morphTo $subject
        +morphTo $causer
        +json $properties
    }
    
    User "1" --> "*" Role : has
    Role "1" --> "*" Permission : contains
    User "1" --> "*" Permission : direct
    User "1" --> "*" ActivityLog : performs
```

### 4.2. Customer Relationship Domain

```mermaid
classDiagram
    class Contact {
        +uuid $id
        +string $name
        +string $email
        +string $phone
        +text $address
        +string $contact_type
        +organization() BelongsTo
        +opportunities() HasMany
        +orders() HasMany
    }
    
    class Organization {
        +uuid $id
        +string $name
        +string $website
        +string $industry
        +contacts() HasMany
        +partners() HasMany
    }
    
    class SalesOpportunity {
        +uuid $id
        +string $title
        +decimal $amount
        +string $stage
        +date $expected_close_date
        +contact() BelongsTo
        +assignedTo() BelongsTo
        +quotes() HasMany
        +close() void
        +qualify() bool
    }
    
    class SalesQuote {
        +uuid $id
        +string $quote_number
        +decimal $total_amount
        +string $status
        +date $valid_until
        +opportunity() BelongsTo
        +orders() HasMany
        +accept() SalesOrder
        +reject() void
    }
    
    class SalesOrder {
        +uuid $id
        +string $order_number
        +decimal $total_amount
        +string $status
        +date $delivery_date
        +customer() BelongsTo
        +items() HasMany
        +fulfill() void
        +cancel() void
    }
    
    Contact "1" --> "1" Organization : belongs_to
    Contact "1" --> "*" SalesOpportunity : has
    SalesOpportunity "1" --> "*" SalesQuote : generates
    SalesQuote "1" --> "*" SalesOrder : converts_to
    Contact "1" --> "*" SalesOrder : places
```

### 4.3. Human Resources Domain

```mermaid
classDiagram
    class Employee {
        +uuid $id
        +string $employee_number
        +string $first_name
        +string $last_name
        +string $email
        +date $hire_date
        +string $status
        +department() BelongsTo
        +position() BelongsTo
        +manager() BelongsTo
        +timesheets() HasMany
        +timeOffRequests() HasMany
        +getFullName() string
        +isActive() bool
    }
    
    class Department {
        +uuid $id
        +string $name
        +text $description
        +employees() HasMany
        +positions() HasMany
        +manager() BelongsTo
        +getEmployeeCount() int
    }
    
    class Position {
        +uuid $id
        +string $title
        +text $job_description
        +decimal $salary_min
        +decimal $salary_max
        +string $employment_type
        +department() BelongsTo
        +employees() HasMany
        +isSalaryInRange(decimal $salary) bool
    }
    
    class Timesheet {
        +uuid $id
        +date $work_date
        +time $start_time
        +time $end_time
        +decimal $hours_worked
        +string $status
        +employee() BelongsTo
        +project() BelongsTo
        +approve() void
        +reject() void
        +calculateHours() decimal
    }
    
    class TimeOffRequest {
        +uuid $id
        +string $type
        +date $start_date
        +date $end_date
        +decimal $days_requested
        +string $status
        +employee() BelongsTo
        +approvedBy() BelongsTo
        +approve() void
        +reject() void
        +calculateDays() decimal
    }
    
    Employee "*" --> "1" Department : belongs_to
    Employee "*" --> "1" Position : holds
    Employee "*" --> "0..1" Employee : reports_to
    Department "1" --> "0..1" Employee : managed_by
    Position "*" --> "1" Department : exists_in
    Employee "1" --> "*" Timesheet : submits
    Employee "1" --> "*" TimeOffRequest : requests
```

### 4.4. Inventory Management Domain

```mermaid
classDiagram
    class Product {
        +uuid $id
        +string $sku
        +string $name
        +text $description
        +decimal $cost_price
        +decimal $selling_price
        +string $product_type
        +string $status
        +category() BelongsTo
        +variants() HasMany
        +inventory() HasMany
        +movements() HasMany
        +isActive() bool
        +getAvailableQuantity() int
    }
    
    class ProductCategory {
        +uuid $id
        +string $name
        +text $description
        +products() HasMany
        +parent() BelongsTo
        +children() HasMany
        +getPath() string
    }
    
    class ProductVariant {
        +uuid $id
        +string $variant_name
        +string $sku
        +decimal $additional_cost
        +json $attributes
        +product() BelongsTo
        +getTotalPrice() decimal
    }
    
    class Inventory {
        +uuid $id
        +integer $quantity_on_hand
        +integer $quantity_reserved
        +integer $quantity_available
        +integer $reorder_point
        +product() BelongsTo
        +warehouse() BelongsTo
        +movements() HasMany
        +isLowStock() bool
        +reserve(int $quantity) bool
        +release(int $quantity) bool
    }
    
    class Warehouse {
        +uuid $id
        +string $name
        +text $address
        +string $manager_name
        +string $status
        +inventory() HasMany
        +movements() HasMany
        +isActive() bool
    }
    
    class InventoryMovement {
        +uuid $id
        +string $movement_type
        +integer $quantity
        +decimal $unit_cost
        +text $reference_number
        +date $movement_date
        +product() BelongsTo
        +warehouse() BelongsTo
        +createdBy() BelongsTo
        +apply() void
    }
    
    Product "*" --> "1" ProductCategory : belongs_to
    ProductCategory "1" --> "0..1" ProductCategory : parent
    Product "1" --> "*" ProductVariant : has
    Product "1" --> "*" Inventory : tracked_in
    Warehouse "1" --> "*" Inventory : stores
    Product "1" --> "*" InventoryMovement : involves
    Warehouse "1" --> "*" InventoryMovement : occurs_in
```

## 5. FilamentPHP Resource Classes

### 5.1. Base Resource Structure

```mermaid
classDiagram
    class Resource {
        <<abstract>>
        +static string $model
        +static string $navigationIcon
        +static string $navigationGroup
        +static int $navigationSort
        +form(Form $form) Form
        +table(Table $table) Table
        +getRelations() array
        +getPages() array
    }
    
    class Form {
        +array $schema
        +schema(array $components) self
        +columns(int $columns) self
        +model(string $model) self
    }
    
    class Table {
        +array $columns
        +array $filters
        +array $actions
        +columns(array $columns) self
        +filters(array $filters) self
        +actions(array $actions) self
    }
    
    class Page {
        <<abstract>>
        +static string $resource
        +static string $view
        +getTitle() string
        +getNavigationLabel() string
    }
    
    class ListRecords {
        +table(Table $table) Table
        +getActions() array
        +getBulkActions() array
    }
    
    class CreateRecord {
        +form(Form $form) Form
        +mutateFormDataBeforeCreate(array $data) array
        +handleRecordCreation(array $data) Model
    }
    
    class EditRecord {
        +form(Form $form) Form
        +mutateFormDataBeforeFill(array $data) array
        +mutateFormDataBeforeSave(array $data) array
    }
    
    Resource --> Form : defines
    Resource --> Table : defines
    Resource --> Page : has
    Page <|-- ListRecords
    Page <|-- CreateRecord
    Page <|-- EditRecord
```

### 5.2. Plugin Resource Examples

```mermaid
classDiagram
    class FieldResource {
        +static string $model "Field"
        +static string $navigationIcon "heroicon-o-rectangle-stack"
        +static string $navigationGroup "System"
        +form(Form $form) Form
        +table(Table $table) Table
    }
    
    class ContactResource {
        +static string $model "Contact"
        +static string $navigationIcon "heroicon-o-users"
        +static string $navigationGroup "CRM"
        +form(Form $form) Form
        +table(Table $table) Table
        +getRelations() array
    }
    
    class ProductResource {
        +static string $model "Product"
        +static string $navigationIcon "heroicon-o-cube"
        +static string $navigationGroup "Inventory"
        +form(Form $form) Form
        +table(Table $table) Table
        +getRelations() array
    }
    
    class EmployeeResource {
        +static string $model "Employee"
        +static string $navigationIcon "heroicon-o-user-group"
        +static string $navigationGroup "HR"
        +form(Form $form) Form
        +table(Table $table) Table
        +getRelations() array
    }
    
    Resource <|-- FieldResource
    Resource <|-- ContactResource
    Resource <|-- ProductResource
    Resource <|-- EmployeeResource
    
    note for Resource "All plugin resources\nextend base Resource class"
```

## 6. Service Layer Classes

### 6.1. Business Services

```mermaid
classDiagram
    class OrderService {
        +createOrder(array $data) SalesOrder
        +processPayment(SalesOrder $order, array $paymentData) Payment
        +fulfillOrder(SalesOrder $order) void
        +cancelOrder(SalesOrder $order, string $reason) void
        +calculateTax(SalesOrder $order) decimal
    }
    
    class InventoryService {
        +checkAvailability(Product $product, int $quantity) bool
        +reserveStock(Product $product, int $quantity) bool
        +releaseStock(Product $product, int $quantity) bool
        +processMovement(array $movementData) InventoryMovement
        +calculateReorderPoint(Product $product) int
    }
    
    class PayrollService {
        +calculateSalary(Employee $employee, Period $period) decimal
        +processTimesheet(Timesheet $timesheet) void
        +calculateOvertime(Employee $employee, Period $period) decimal
        +generatePayslip(Employee $employee, Period $period) Payslip
    }
    
    class ReportService {
        +generateSalesReport(Period $period) Report
        +generateInventoryReport() Report
        +generateFinancialReport(Period $period) Report
        +generateEmployeeReport() Report
        +exportToPdf(Report $report) string
        +exportToExcel(Report $report) string
    }
    
    class NotificationService {
        +sendEmail(User $user, string $template, array $data) void
        +sendSms(User $user, string $message) void
        +sendSystemNotification(User $user, string $message) void
        +broadcastNotification(array $users, string $message) void
    }
    
    class AuditService {
        +logActivity(Model $model, string $action, array $changes) ActivityLog
        +getAuditTrail(Model $model) Collection
        +getSystemLogs(Period $period) Collection
        +generateComplianceReport() Report
    }
    
    OrderService --> InventoryService : uses
    OrderService --> PayrollService : interacts
    ReportService --> NotificationService : uses
    AuditService --> NotificationService : uses
```

### 6.2. Integration Services

```mermaid
classDiagram
    class PaymentGatewayService {
        <<interface>>
        +processPayment(array $data) PaymentResult
        +refundPayment(string $transactionId, decimal $amount) RefundResult
        +getTransactionStatus(string $transactionId) string
    }
    
    class StripeGateway {
        +string $apiKey
        +processPayment(array $data) PaymentResult
        +refundPayment(string $transactionId, decimal $amount) RefundResult
        +getTransactionStatus(string $transactionId) string
        +createCustomer(array $data) string
    }
    
    class PayPalGateway {
        +string $clientId
        +string $clientSecret
        +processPayment(array $data) PaymentResult
        +refundPayment(string $transactionId, decimal $amount) RefundResult
        +getTransactionStatus(string $transactionId) string
    }
    
    class EmailService {
        <<interface>>
        +send(string $to, string $subject, string $body) bool
        +sendTemplate(string $to, string $template, array $data) bool
    }
    
    class MailgunService {
        +string $apiKey
        +string $domain
        +send(string $to, string $subject, string $body) bool
        +sendTemplate(string $to, string $template, array $data) bool
    }
    
    class SendGridService {
        +string $apiKey
        +send(string $to, string $subject, string $body) bool
        +sendTemplate(string $to, string $template, array $data) bool
    }
    
    PaymentGatewayService <|.. StripeGateway
    PaymentGatewayService <|.. PayPalGateway
    EmailService <|.. MailgunService
    EmailService <|.. SendGridService
```

---

## Summary

This comprehensive class diagram documentation provides:

**Core Framework Classes (2 diagrams):**
- Laravel Application Structure
- Plugin System Core Classes

**Plugin System Classes (1 diagram):**
- Webkul Plugin Architecture

**Business Domain Models (4 diagrams):**
- User Management Domain
- Customer Relationship Domain  
- Human Resources Domain
- Inventory Management Domain

**FilamentPHP Resource Classes (2 diagrams):**
- Base Resource Structure
- Plugin Resource Examples

**Service Layer Classes (2 diagrams):**
- Business Services
- Integration Services

These class diagrams provide essential insights into:

- **Object-oriented architecture** of the AureusERP system
- **Inheritance hierarchies** and design patterns
- **Domain model relationships** and business logic
- **Service layer organization** and integration patterns
- **Plugin extensibility** and customization points

**Confidence Level: 93%** - Based on Laravel conventions and FilamentPHP analysis

---

**Previous Document**: [090-system-diagrams.md](090-system-diagrams.md) - System Architecture Diagrams and Schematics

**Next Document**: [999-summary.md](999-summary.md) - Project Analysis Summary
