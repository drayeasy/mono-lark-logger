<?php

namespace Drayeasy\MonoLarkLogger\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'monolark:install')]
class MonoLarkLoggerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monolark:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Mono-Lark-Logger resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->components->info('Installing Mono-Lark-Logger resources.');

        collect([
            'Logging Code' => fn() => $this->callSilent('vendor:publish', ['--tag' => 'logging']) == 0,
            'Service Provider' => fn() => $this->callSilent('vendor:publish', ['--tag' => 'providers']) == 0,
        ])->each(fn($task, $description) => $this->components->task($description, $task));

        $this->registerServiceProvider();

        $this->components->info('Mono-Lark-Logger scaffolding installed successfully.');
    }


    protected function registerServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        if (file_exists($this->laravel->bootstrapPath('providers.php'))) {
            ServiceProvider::addProviderToBootstrapFile("{$namespace}\\Providers\\MonoLarkLoggerServiceProvider");
        } else {
            $appConfig = file_get_contents(config_path('app.php'));

            if (Str::contains($appConfig, $namespace . '\\Providers\\MonoLarkLoggerServiceProvider::class')) {
                return;
            }

            file_put_contents(
                config_path('app.php'),
                str_replace(
                    "{$namespace}\\Providers\AppServiceProvider::class," . PHP_EOL,
                    "{$namespace}\\Providers\AppServiceProvider::class," . PHP_EOL . "        {$namespace}\Providers\MonoLarkLoggerServiceProvider::class," . PHP_EOL,
                    $appConfig
                )
            );
        }

        file_put_contents(
            app_path('Providers/MonoLarkLoggerServiceProvider.php'),
            str_replace(
                "namespace App\Providers;",
                "namespace {$namespace}\Providers;",
                file_get_contents(app_path('Providers/MonoLarkLoggerServiceProvider.php'))
            )
        );
    }
}


