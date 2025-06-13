<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('inventories_logistic.enable_dropshipping', false);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('inventories_logistic.enable_dropshipping');
    }
};
