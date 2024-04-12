<?php

namespace Drayeasy\MonoLarkLogger\Commands;

use Illuminate\Console\Command;
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

    $this->components->info('Mono-Lark-Logger scaffolding installed successfully.');
  }

}


