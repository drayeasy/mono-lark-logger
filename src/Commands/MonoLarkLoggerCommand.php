<?php

namespace Drayeasy\MonoLarkLogger\Commands;

use Illuminate\Console\Command;

class MonoLarkLoggerCommand extends Command
{
    public $signature = 'mono-lark-logger';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
