<?php

namespace Frontier\Actions;

use Illuminate\Database\Eloquent\Model;

class UpdateOrCreateAction extends EloquentAction
{
    public function handle(array $conditions, array $values): Model
    {
        return $this->model->query()->updateOrCreate($conditions, $values);
    }
}
