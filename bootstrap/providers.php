<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\CustomerPanelProvider;
use Webkul\Account\AccountServiceProvider;
use Webkul\Analytic\AnalyticServiceProvider;
use Webkul\Blog\BlogServiceProvider;
use Webkul\Chatter\ChatterServiceProvider;
use Webkul\Contact\ContactServiceProvider;
use Webkul\Employee\EmployeeServiceProvider;
use Webkul\Field\FieldServiceProvider;
use Webkul\Inventory\InventoryServiceProvider;
use Webkul\Invoice\InvoiceServiceProvider;
use Webkul\Partner\PartnerServiceProvider;
use Webkul\Payment\PaymentServiceProvider;
use Webkul\Product\ProductServiceProvider;
use Webkul\Project\ProjectServiceProvider;
use Webkul\Purchase\PurchaseServiceProvider;
use Webkul\Recruitment\RecruitmentServiceProvider;
use Webkul\Sale\SaleServiceProvider;
use Webkul\Security\SecurityServiceProvider;
use Webkul\Support\SupportServiceProvider;
use Webkul\TableViews\TableViewsServiceProvider;
use Webkul\TimeOff\TimeOffServiceProvider;
use Webkul\Timesheet\TimesheetServiceProvider;
use Webkul\Website\WebsiteServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    CustomerPanelProvider::class,
    AnalyticServiceProvider::class,
    AccountServiceProvider::class,
    BlogServiceProvider::class,
    ChatterServiceProvider::class,
    ContactServiceProvider::class,
    EmployeeServiceProvider::class,
    SupportServiceProvider::class,
    FieldServiceProvider::class,
    InventoryServiceProvider::class,
    InvoiceServiceProvider::class,
    PartnerServiceProvider::class,
    PaymentServiceProvider::class,
    ProductServiceProvider::class,
    ProjectServiceProvider::class,
    PurchaseServiceProvider::class,
    TableViewsServiceProvider::class,
    RecruitmentServiceProvider::class,
    SaleServiceProvider::class,
    SecurityServiceProvider::class,
    TimeOffServiceProvider::class,
    TimesheetServiceProvider::class,
    WebsiteServiceProvider::class,
];
