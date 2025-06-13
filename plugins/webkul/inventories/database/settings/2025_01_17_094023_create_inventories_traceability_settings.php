<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('inventories_traceability.enable_lots_serial_numbers', false);
        $this->migrator->add('inventories_traceability.enable_expiration_dates', false);
        $this->migrator->add('inventories_traceability.display_on_delivery_slips', false);
        $this->migrator->add('inventories_traceability.display_expiration_dates_on_delivery_slips', false);
        $this->migrator->add('inventories_traceability.enable_consignments', false);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('inventories_traceability.enable_lots_serial_numbers');
        $this->migrator->deleteIfExists('inventories_traceability.enable_expiration_dates');
        $this->migrator->deleteIfExists('inventories_traceability.display_on_delivery_slips');
        $this->migrator->deleteIfExists('inventories_traceability.display_expiration_dates_on_delivery_slips');
        $this->migrator->deleteIfExists('inventories_traceability.enable_consignments');
    }
};
