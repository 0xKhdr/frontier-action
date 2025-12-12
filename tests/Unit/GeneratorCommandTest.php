<?php

declare(strict_types=1);

use Frontier\Actions\Console\Commands\GeneratorCommand;
use Illuminate\Console\Command;

describe('GeneratorCommand', function () {
    it('extends Laravel Command', function () {
        $command = new class extends GeneratorCommand
        {
            protected $signature = 'test:command {name}';

            public function getSourceFilePath(): string
            {
                return '/tmp/test.php';
            }

            public function getStubPath(): string
            {
                return __DIR__.'/../../stubs/test.stub';
            }
        };

        expect($command)->toBeInstanceOf(Command::class);
    });

    it('has getClassName method', function () {
        expect(method_exists(GeneratorCommand::class, 'getClassName'))->toBeTrue();
    });

    it('has getSingularClassName method', function () {
        expect(method_exists(GeneratorCommand::class, 'getSingularClassName'))->toBeTrue();
    });

    it('has makeDirectory method', function () {
        expect(method_exists(GeneratorCommand::class, 'makeDirectory'))->toBeTrue();
    });
});
