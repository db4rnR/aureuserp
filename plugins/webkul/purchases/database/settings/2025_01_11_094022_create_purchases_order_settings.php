<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('purchases_order.enable_order_approval', false);
        $this->migrator->add('purchases_order.order_validation_amount', 5000);
        $this->migrator->add('purchases_order.enable_lock_confirmed_orders', false);
        $this->migrator->add('purchases_order.enable_purchase_agreements', false);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('purchases_order.enable_order_approval');
        $this->migrator->deleteIfExists('purchases_order.order_validation_amount');
        $this->migrator->deleteIfExists('purchases_order.enable_lock_confirmed_orders');
        $this->migrator->deleteIfExists('purchases_order.enable_purchase_agreements');
    }
};
