<?php

declare(strict_types=1);

use Frontier\Actions\BaseAction;
use Frontier\Actions\Contracts\Action;

describe('Action Contract', function (): void {
    it('is implemented by BaseAction', function (): void {
        $action = new class extends BaseAction
        {
            public function handle(): string
            {
                return 'test';
            }
        };

        expect($action)->toBeInstanceOf(Action::class);
    });

    it('requires exec method', function (): void {
        expect(method_exists(Action::class, 'exec'))->toBeTrue();
    });

    it('requires execute method', function (): void {
        expect(method_exists(Action::class, 'execute'))->toBeTrue();
    });
});
