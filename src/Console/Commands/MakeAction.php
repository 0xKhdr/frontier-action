<?php

namespace Frontier\Actions\Console\Commands;

use Illuminate\Support\Facades\App;

class MakeAction extends AbstractMake
{
    protected $signature = 'frontier:action {name}';

    protected $description = 'Create a new action class';

    public function getSourceFilePath(): string
    {
        return App::path('Actions/'.$this->getClassName()).'.php';
    }

    public function getStubPath(): string
    {
        return __DIR__.'/../../../resources/stubs/action.stub';
    }

    public function getStubVariables(): array
    {
        return [
            'NAMESPACE' => 'App\\Actions',
            'CLASS_NAME' => $this->getClassName(),
        ];
    }
}
