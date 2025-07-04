<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('time.enable_timesheets', false);
    }

    public function down(): void
    {
        $this->migrator->delete('time.enable_timesheets');
    }
};
