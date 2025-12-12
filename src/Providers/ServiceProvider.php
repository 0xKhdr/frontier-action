<?php

declare(strict_types=1);

namespace Frontier\Actions\Providers;

use Frontier\Actions\Console\Commands\MakeAction;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /** @var array<int, class-string> */
    protected array $commands = [
        MakeAction::class,
    ];

    public function register(): void {}

    public function boot(): void
    {
        $this->commands($this->commands);
    }
}
