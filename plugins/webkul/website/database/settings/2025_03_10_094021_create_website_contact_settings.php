<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('website_contact.email', 'support@example.com');
        $this->migrator->add('website_contact.phone', '+1234567890');
        $this->migrator->add('website_contact.twitter', 'username');
        $this->migrator->add('website_contact.facebook', 'username');
        $this->migrator->add('website_contact.instagram', 'username');
        $this->migrator->add('website_contact.linkedin', 'username');
        $this->migrator->add('website_contact.pinterest', 'username');
        $this->migrator->add('website_contact.tiktok', 'username');
        $this->migrator->add('website_contact.github', 'username');
        $this->migrator->add('website_contact.slack', 'username');
        $this->migrator->add('website_contact.whatsapp', 'username');
        $this->migrator->add('website_contact.youtube', 'username');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('website_contact.email');
        $this->migrator->deleteIfExists('website_contact.phone');
        $this->migrator->deleteIfExists('website_contact.twitter');
        $this->migrator->deleteIfExists('website_contact.facebook');
        $this->migrator->deleteIfExists('website_contact.instagram');
        $this->migrator->deleteIfExists('website_contact.linkedin');
        $this->migrator->deleteIfExists('website_contact.pinterest');
        $this->migrator->deleteIfExists('website_contact.tiktok');
        $this->migrator->deleteIfExists('website_contact.github');
        $this->migrator->deleteIfExists('website_contact.slack');
        $this->migrator->deleteIfExists('website_contact.whatsapp');
        $this->migrator->deleteIfExists('website_contact.youtube');
    }
};
