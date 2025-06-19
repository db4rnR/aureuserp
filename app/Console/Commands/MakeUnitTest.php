<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeUnitTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:unit-test
                            {name : The name of the model to test}
                            {--plugin= : The plugin name}
                            {--path= : The path where the test should be created}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new unit test based on the standard template';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $modelName = $this->argument('name');
        $pluginName = $this->option('plugin') ?? $this->ask('What is the plugin name?');
        $path = $this->option('path') ?? "tests/Unit/Plugins/{$pluginName}/Models";

        // Create the directory if it doesn't exist
        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Get the template content
        $templatePath = base_path('.junie/guidelines/test-templates/unit-test-template.php');
        $template = File::get($templatePath);

        // Replace placeholders in the template
        $content = str_replace('YourPlugin', $pluginName, $template);
        $content = str_replace('YourModel', $modelName, $content);
        $content = str_replace('your-plugin', Str::kebab($pluginName), $content);
        $content = str_replace('your_model', Str::snake($modelName), $content);

        // Create the test file
        $testPath = "{$path}/{$modelName}Test.php";

        if (File::exists($testPath) && ! $this->confirm("The file {$testPath} already exists. Do you want to overwrite it?")) {
            $this->info('Command canceled.');

            return;
        }

        File::put($testPath, $content);

        $this->info("Unit test created successfully: {$testPath}");
    }
}
