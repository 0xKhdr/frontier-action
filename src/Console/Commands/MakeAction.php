<?php

namespace Frontier\Actions\Console\Commands;

use Illuminate\Console\Command;

class MakeAction extends Command
{
    protected $signature = 'frontier:action {name}';

    protected $description = 'Create a new action class';

    public function handle(): int
    {
        $name = $this->argument('name');

        return 0;
    }
}
