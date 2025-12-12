<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

describe('MakeAction Command', function () {
    afterEach(function () {
        $testPath = app_path('Actions/TestGeneratedAction.php');
        if (file_exists($testPath)) {
            unlink($testPath);
        }
    });

    it('is registered', function () {
        $commands = Artisan::all();

        expect($commands)->toHaveKey('frontier:action');
    });

    it('has module option', function () {
        $command = Artisan::all()['frontier:action'];
        $definition = $command->getDefinition();

        expect($definition->hasOption('module'))->toBeTrue();
    });

    it('creates action file', function () {
        $this->artisan('frontier:action', ['name' => 'TestGeneratedAction'])
            ->assertSuccessful();

        expect(file_exists(app_path('Actions/TestGeneratedAction.php')))->toBeTrue();
    });

    it('generates correct namespace', function () {
        $this->artisan('frontier:action', ['name' => 'TestGeneratedAction'])
            ->assertSuccessful();

        $content = file_get_contents(app_path('Actions/TestGeneratedAction.php'));

        expect($content)
            ->toContain('namespace App\Actions;')
            ->toContain('class TestGeneratedAction extends BaseAction')
            ->toContain('declare(strict_types=1);');
    });
});
