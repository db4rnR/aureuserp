<?php

use Webkul\Account\AccountPlugin;
use Webkul\Blog\BlogPlugin;
use Webkul\Chatter\ChatterPlugin;
use Webkul\Contact\ContactPlugin;
use Webkul\Employee\EmployeePlugin;
use Webkul\Inventory\InventoryPlugin;
use Webkul\Invoice\InvoicePlugin;
use Webkul\Payment\PaymentPlugin;
use Webkul\Partner\PartnerPlugin;
use Webkul\Project\ProjectPlugin;
use Webkul\Purchase\PurchasePlugin;
use Webkul\Support\SupportPlugin;
use Webkul\Recruitment\RecruitmentPlugin;
use Webkul\Sale\SalePlugin;
use Webkul\Security\SecurityPlugin;
use Webkul\Field\FieldsPlugin;
use Webkul\TimeOff\TimeOffPlugin;
use Webkul\Timesheet\TimesheetPlugin;
use Webkul\Website\WebsitePlugin;

return [
    AccountPlugin::class,
    BlogPlugin::class,
    ChatterPlugin::class,
    ContactPlugin::class,
    EmployeePlugin::class,
    InventoryPlugin::class,
    InvoicePlugin::class,
    // Webkul\Product\ProductPlugin::class,
    PaymentPlugin::class,
    PartnerPlugin::class,
    ProjectPlugin::class,
    PurchasePlugin::class,
    SupportPlugin::class,
    RecruitmentPlugin::class,
    SalePlugin::class,
    SecurityPlugin::class,
    FieldsPlugin::class,
    PartnerPlugin::class,
    TimeOffPlugin::class,
    TimesheetPlugin::class,
    WebsitePlugin::class,
];
