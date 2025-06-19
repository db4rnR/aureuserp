<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeIntegrationTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:integration-test
                            {name : The name of the service to test}
                            {--plugin= : The plugin name}
                            {--path= : The path where the test should be created}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new integration test based on the standard template';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $serviceName = $this->argument('name');
        $pluginName = $this->option('plugin') ?? $this->ask('What is the plugin name?');
        $path = $this->option('path') ?? "tests/Integration/Plugins/{$pluginName}";

        // Create the directory if it doesn't exist
        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Get the template content
        $templatePath = base_path('.junie/guidelines/test-templates/integration-test-template.php');
        $template = File::get($templatePath);

        // Replace placeholders in the template
        $content = str_replace('YourPlugin', $pluginName, $template);
        $content = str_replace('YourService', $serviceName, $content);
        $content = str_replace('your-plugin', Str::kebab($pluginName), $content);
        $content = str_replace('your_service', Str::snake($serviceName), $content);

        // Create the test file
        $testPath = "{$path}/{$serviceName}Test.php";

        if (File::exists($testPath) && ! $this->confirm("The file {$testPath} already exists. Do you want to overwrite it?")) {
            $this->info('Command canceled.');

            return;
        }

        File::put($testPath, $content);

        $this->info("Integration test created successfully: {$testPath}");
    }
}
