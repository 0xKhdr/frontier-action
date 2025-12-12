<?php

declare(strict_types=1);

use Frontier\Actions\EloquentAction;
use Illuminate\Database\Eloquent\Model;

class ConcreteEloquentAction extends EloquentAction
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function handle(): Model
    {
        return $this->model;
    }

    public function getModel(): Model
    {
        return $this->model;
    }
}

class TestModel extends Model
{
    protected $table = 'test_models';
}

describe('EloquentAction', function (): void {
    it('extends BaseAction', function (): void {
        $action = new ConcreteEloquentAction(new TestModel);

        expect($action)->toBeInstanceOf(EloquentAction::class);
    });

    it('has model property', function (): void {
        $model = new TestModel;
        $action = new ConcreteEloquentAction($model);

        expect($action->getModel())->toBeInstanceOf(Model::class);
    });
});
