<?php

declare(strict_types=1);

use Frontier\Actions\BaseAction;
use Frontier\Actions\Contracts\Action;

describe('Action Contract', function () {
    it('is implemented by BaseAction', function () {
        $action = new class extends BaseAction
        {
            public function handle(): string
            {
                return 'test';
            }
        };

        expect($action)->toBeInstanceOf(Action::class);
    });

    it('requires exec method', function () {
        expect(method_exists(Action::class, 'exec'))->toBeTrue();
    });

    it('requires execute method', function () {
        expect(method_exists(Action::class, 'execute'))->toBeTrue();
    });
});
