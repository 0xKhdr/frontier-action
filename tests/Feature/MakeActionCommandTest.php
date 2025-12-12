<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

describe('MakeAction Command', function (): void {
    afterEach(function (): void {
        $testPath = app_path('Actions/TestGeneratedAction.php');
        if (file_exists($testPath)) {
            unlink($testPath);
        }
    });

    it('is registered', function (): void {
        $commands = Artisan::all();

        expect($commands)->toHaveKey('frontier:action');
    });

    it('has module option', function (): void {
        $command = Artisan::all()['frontier:action'];
        $definition = $command->getDefinition();

        expect($definition->hasOption('module'))->toBeTrue();
    });

    it('creates action file', function (): void {
        $this->artisan('frontier:action', ['name' => 'TestGeneratedAction'])
            ->assertSuccessful();

        expect(file_exists(app_path('Actions/TestGeneratedAction.php')))->toBeTrue();
    });

    it('generates correct namespace', function (): void {
        $this->artisan('frontier:action', ['name' => 'TestGeneratedAction'])
            ->assertSuccessful();

        $content = file_get_contents(app_path('Actions/TestGeneratedAction.php'));

        expect($content)
            ->toContain('namespace App\Actions;')
            ->toContain('class TestGeneratedAction extends BaseAction')
            ->toContain('declare(strict_types=1);');
    });
});
