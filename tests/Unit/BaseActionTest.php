<?php

declare(strict_types=1);

use Frontier\Actions\BaseAction;

// Test action classes for testing
class TestAction extends BaseAction
{
    public function handle(string $value): string
    {
        return 'handled: '.$value;
    }
}

class ActionWithoutHandle extends BaseAction
{
    // Missing handle method intentionally
}

class MultiArgAction extends BaseAction
{
    public function handle(string $a, string $b, string $c): string
    {
        return "{$a}-{$b}-{$c}";
    }
}

describe('BaseAction', function () {
    it('can be executed with exec method', function () {
        $result = TestAction::exec('test-value');

        expect($result)->toBe('handled: test-value');
    });

    it('can be executed with execute method', function () {
        $action = new TestAction;
        $result = $action->execute('test-value');

        expect($result)->toBe('handled: test-value');
    });

    it('throws exception without handle method', function () {
        $action = new ActionWithoutHandle;
        $action->execute();
    })->throws(BadMethodCallException::class, 'must implement the handle() method');

    it('receives multiple arguments', function () {
        $result = MultiArgAction::exec('arg1', 'arg2', 'arg3');

        expect($result)->toBe('arg1-arg2-arg3');
    });
});
